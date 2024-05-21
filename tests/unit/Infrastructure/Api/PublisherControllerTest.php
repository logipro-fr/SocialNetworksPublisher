<?php

namespace SocialNetworksPublisher\Tests\Infrastructure\Api;

use SocialNetworksPublisher\Infrastructure\Api\V1\PublisherController;
use SocialNetworksPublisher\Infrastructure\Persistence\PostRepositoryInMemory;
use SocialNetworksPublisher\Infrastructure\SimpleBlog;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use function Safe\getcwd;

class PublisherControllerTest extends WebTestCase
{
    public function testControllerRouting(): void
    {
        $client = static::createClient();
        $client->request(
            "GET",
            "/api/v1/publishPost",
            [
                "socialNetworks" => "simpleBlog",
                "authorId" => "1584514",
                "pageId" => "4a75fe6",
                "content" => "Ceci est un simple post",
                "hashtag" => "#test, #FizzBuzz",
            ]
        );
        /** @var string */
        $responseContent = $client->getResponse()->getContent();

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('"success":true', $responseContent);
        $this->assertStringContainsString('"statusCode":201', $responseContent);
        $this->assertStringContainsString('"postId":"pos_', $responseContent);
        $this->assertStringContainsString('"message":"', $responseContent);
    }

    public function testControllerErrorResponse(): void
    {
        $client = static::createClient();
        $client->request(
            "GET",
            "/api/v1/publishPost",
            [
                "socialNetworks" => "",
                "authorId" => "1584514",
                "pageId" => "4a75fe6",
                "content" => "Ceci est un simple post",
                "hashtag" => "#test, #FizzBuzz",
            ]
        );
        /** @var string */
        $responseContent = $client->getResponse()->getContent();

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('"succes":false', $responseContent);
        $this->assertStringContainsString('"statusCode":422', $responseContent);
        $this->assertStringContainsString('"message":"', $responseContent);
    }

    public function testExecute(): void
    {
        $controller = new PublisherController(
            new SimpleBlog(
                getcwd() . "/var/simpleBlog.txt"
            ),
            new PostRepositoryInMemory()
        );
        $request = Request::create(
            "/api/v1/publishPost",
            "GET",
            [
                "socialNetworks" => "simpleBlog",
                "authorId" => "1584514",
                "pageId" => "4a75fe6",
                "content" => "Ceci est un simple post",
                "hashtag" => "#test, #FizzBuzz",
            ]
        );

        $response = $controller->execute($request);
        /** @var string */
        $responseContent = $response->getContent();

        $this->assertJson($responseContent);
    }
}
