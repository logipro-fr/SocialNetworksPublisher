<?php

namespace SocialNetworksPublisher\Tests\Infrastructure\Api;

use DoctrineTestingTools\DoctrineRepositoryTesterTrait;
use SocialNetworksPublisher\Domain\Model\Post\PostRepositoryInterface;
use SocialNetworksPublisher\Infrastructure\Api\V1\PublisherController;
use SocialNetworksPublisher\Infrastructure\Persistence\Post\PostRepositoryInMemory;
use SocialNetworksPublisher\Infrastructure\Provider\SimpleBlog\SimpleBlog;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use function Safe\getcwd;

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

    public function testControllerRouting(): void
    {
        $this->client->request(
            "POST",
            "/api/v1/post/publish",
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                "socialNetworks" => "simpleBlog",
                "authorId" => "1584514",
                "pageId" => "4a75fe6",
                "content" => "Ceci est un simple post",
                "hashtag" => "#test, #FizzBuzz",
            ])
        );
        /** @var string */
        $responseContent = $this->client->getResponse()->getContent();
        $responseCode = $this->client->getResponse()->getStatusCode();
        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('"success":true', $responseContent);
        $this->assertEquals(201, $responseCode);
        $this->assertStringContainsString('"ErrorCode":', $responseContent);
        $this->assertStringContainsString('"postId":"pos_', $responseContent);
        $this->assertStringContainsString('"socialNetworks":"simpleBlog', $responseContent);
        $this->assertStringContainsString('"message":"', $responseContent);
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
            '"message":"The social network parameters cannot be empty"',
            $responseContent
        );
    }

    public function testExecute(): void
    {
        $controller = new PublisherController(
            new SimpleBlog(
                getcwd() . "/var/simpleBlog.txt"
            ),
            $this->repository,
            $this->getEntityManager(),
        );
        $request = Request::create(
            "/api/v1/publishPost",
            "POST",
            [],
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

        $response = $controller->execute($request);
        /** @var string */
        $responseContent = $response->getContent();
        $this->assertJson($responseContent);
    }
}
