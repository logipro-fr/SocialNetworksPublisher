<?php

namespace SocialNetworksPublisher\Tests\Infrastructure\Api;

use SocialNetworksPublisher\Infrastructure\Api\V1\PublisherController;
use SocialNetworksPublisher\Infrastructure\Persistence\PostRepositoryInMemory;
use SocialNetworksPublisher\Infrastructure\Provider\SimpleBlog\SimpleBlog;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use function Safe\getcwd;

class PublisherControllerTest extends WebTestCase
{
    public function testControllerRouting(): void
    {
        $client = static::createClient();
        $client->request(
            "POST",
            "/api/v1/post/publish",
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
        $responseCode = $client->getResponse()->getStatusCode();
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
        $client = static::createClient();
        $client->request(
            "POST",
            "/api/v1/post/publish",
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
        $responseCode = $client->getResponse()->getStatusCode();
        $this->assertResponseIsUnprocessable();
        $this->assertEquals(422, $responseCode);
        $this->assertStringContainsString('"succes":false', $responseContent);
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
            new PostRepositoryInMemory()
        );
        $request = Request::create(
            "/api/v1/publishPost",
            "POST",
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
