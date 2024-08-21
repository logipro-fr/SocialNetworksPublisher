<?php

namespace SocialNetworksPublisher\Tests\Integration\Infrastructure\Api;

use DoctrineTestingTools\DoctrineRepositoryTesterTrait;
use SocialNetworksPublisher\Domain\Model\Page\PostId;
use SocialNetworksPublisher\Domain\Model\Post\PostRepositoryInterface;
use SocialNetworksPublisher\Infrastructure\Persistence\Post\PostRepositoryDoctrine;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

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

        //$this->assertResponseIsUnprocessable();
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $responseCode);
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

        $repo = new PostRepositoryDoctrine($this->getEntityManager());

        $post = $repo ->findById(new PostId($postId));


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
                "content" => "Ceci est un simple post mais ces le deuxieme qui est posté",
                "hashtag" => "#test, #FizzBuzz",
            ])
        );
        /** @var string */
        $responseContent2 = $this->client->getResponse()->getContent();
        $responseCode2 = $this->client->getResponse()->getStatusCode();
        /** @var array<mixed,array<mixed>> */
        $array2 = json_decode($responseContent2, true);
        /** @var string */
        $postId2 = $array2['data']['postId'];
        $post2 = $this->repository->findById(new PostId($postId2));

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('"success":true', $responseContent);
        $this->assertEquals(201, $responseCode);
        $this->assertStringContainsString('"ErrorCode":', $responseContent);
        $this->assertStringContainsString('"postId":"pos_', $responseContent);
        $this->assertStringContainsString('"socialNetworks":"Twitter', $responseContent);
        $this->assertStringContainsString('"message":"', $responseContent);
        $this->assertEquals("Ceci est un simple post", $post->getContent());

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('"success":true', $responseContent2);
        $this->assertEquals(201, $responseCode2);
        $this->assertStringContainsString('"ErrorCode":', $responseContent2);
        $this->assertStringContainsString('"postId":"pos_', $responseContent2);
        $this->assertStringContainsString('"socialNetworks":"Twitter', $responseContent2);
        $this->assertStringContainsString('"message":"', $responseContent2);
        $this->assertEquals("Ceci est un simple post mais ces le deuxieme qui est posté", $post2->getContent());
    }
}
