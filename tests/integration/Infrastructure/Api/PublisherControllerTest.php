<?php

namespace SocialNetworksPublisher\Tests\Integration\Infrastructure\Api;

use DoctrineTestingTools\DoctrineRepositoryTesterTrait;
use SocialNetworksPublisher\Domain\Model\Post\PostId;
use SocialNetworksPublisher\Domain\Model\Post\PostRepositoryInterface;
use SocialNetworksPublisher\Infrastructure\Api\V1\PublisherController;
use SocialNetworksPublisher\Infrastructure\Persistence\Post\PostRepositoryDoctrine;
use SocialNetworksPublisher\Infrastructure\Persistence\Post\PostRepositoryInMemory;
use SocialNetworksPublisher\Infrastructure\Provider\SimpleBlog\SimpleBlog;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use function Safe\getcwd;
use function Safe\json_encode;

class PublisherControllerTest extends WebTestCase
{
    use DoctrineRepositoryTesterTrait;

    private KernelBrowser $client;
    private PostRepositoryInterface $repository;

    public function setUp(): void
    {
        $this->initDoctrineTester();
        $this->clearTables(["posts"]);

        $this->client = static::createClient(["debug" => false]);

        /** @var PostRepositoryDoctrine $autoInjectedRepo */
        $autoInjectedRepo = $this->client->getContainer()->get("post.repository");
        $this->repository = $autoInjectedRepo;
    }
    public function testControllerErrorResponse(): void
    {
        $this->client->request(
            "POST",
            "/api/v1/post/publish",
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                "socialNetworks" => "",
                "authorId" => "1584514",
                "pageId" => "4a75fe6",
                "content" => "Ceci est un simple post",
                "hashtag" => "#test, #FizzBuzz",
            ])
        );
        /** @var string */
        $responseContent = $this->client->getResponse()->getContent();
        $responseCode = $this->client->getResponse()->getStatusCode();

        $this->assertResponseIsUnprocessable();
        $this->assertEquals(422, $responseCode);
        $this->assertStringContainsString('"success":false', $responseContent);
        $this->assertStringContainsString('"ErrorCode":"BadSocialNetworksParameterException"', $responseContent);
        $this->assertStringContainsString(
            '"message":"Invalid social network"',
            $responseContent
        );
    }
    public function testControllerRouting(): void
    {
        $this->client->request(
            "POST",
            "/api/v1/post/publish",
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                "socialNetworks" => "Twitter",
                "authorId" => "1584514",
                "pageId" => "4a75fe6",
                "content" => "Ceci est un simple post",
                "hashtag" => "#test, #FizzBuzz",
            ])
        );
        /** @var string */
        $responseContent = $this->client->getResponse()->getContent();
        $responseCode = $this->client->getResponse()->getStatusCode();
        /** @var array<mixed,array<mixed>> */
        $array = json_decode($responseContent, true);
        /** @var string */
        $postId = $array['data']['postId'];
        $post = $this->repository->findById(new PostId($postId));

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('"success":true', $responseContent);
        $this->assertEquals(201, $responseCode);
        $this->assertStringContainsString('"ErrorCode":', $responseContent);
        $this->assertStringContainsString('"postId":"pos_', $responseContent);
        $this->assertStringContainsString('"socialNetworks":"Twitter', $responseContent);
        $this->assertStringContainsString('"message":"', $responseContent);
        $this->assertEquals("Ceci est un simple post", $post->getContent());
    }
}
