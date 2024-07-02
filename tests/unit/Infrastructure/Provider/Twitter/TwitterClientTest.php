<?php

namespace SocialNetworksPublisher\Tests\Infrastructure\Provider\Twitter;

use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Application\Service\PublishPost\PostFactory;
use SocialNetworksPublisher\Application\Service\PublishPost\PublishPostRequest;
use SocialNetworksPublisher\Infrastructure\Provider\Exceptions\BadRequestException;
use SocialNetworksPublisher\Infrastructure\Provider\Exceptions\UnauthorizedException;
use SocialNetworksPublisher\Infrastructure\Provider\Twitter\TwitterBearerToken;
use SocialNetworksPublisher\Infrastructure\Provider\Twitter\TwitterClient;
use SocialNetworksPublisher\Infrastructure\Shared\CurrentWorkDirPath;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

use function Safe\file_get_contents;

class TwitterClientTest extends TestCase
{
    private const TEXT_CONTENT = "Hello world !";
    private PublishPostRequest $request;

    protected function setUp(): void
    {
        $this->request = new PublishPostRequest(
            "Twitter",
            "1a84fvb",
            "5adf78bfdsg",
            self::TEXT_CONTENT,
            ""
        );
    }

    protected function tearDown(): void
    {
        $this->cleanup();
    }

    private function cleanup(): void
    {
        @unlink(CurrentWorkDirPath::getPath() . TwitterBearerToken::BEARER_PATH);
        @unlink(CurrentWorkDirPath::getPath() . TwitterBearerToken::REFRESH_PATH);
        @unlink(CurrentWorkDirPath::getPath() . TwitterBearerToken::EXPIRATION_PATH);
    }

    public function testTwitterRequest(): void
    {
        $this->cleanup();

        $post = (new PostFactory())->buildPostFromRequest($this->request);
        $twitterResponse1 = [
            new MockResponse(
                file_get_contents(CurrentWorkDirPath::getPath() .
                 '/tests/unit/ressources/TwitterResponseRefreshFirst.json'),
                ['http_code' => 200]
            ),
        ];
        $twitterResponse2 = [
            new MockResponse(
                file_get_contents(CurrentWorkDirPath::getPath() .
                 '/tests/unit/ressources/TwitterResponseTweet.json'),
                ['http_code' => 200]
            ),
        ];
        $twitterResponse3 = [
            new MockResponse(
                file_get_contents(CurrentWorkDirPath::getPath() .
                 '/tests/unit/ressources/TwitterResponseRefresh.json'),
                ['http_code' => 200]
            ),
            new MockResponse(
                file_get_contents(CurrentWorkDirPath::getPath() .
                 '/tests/unit/ressources/TwitterResponseTweet.json'),
                ['http_code' => 200]
            ),
        ];
        $client1 = new MockHttpClient($twitterResponse1, 'https://api.twitter.com/2/oauth2/token');
        $client2 = new MockHttpClient($twitterResponse2, 'https://api.twitter.com/2/tweets');
        $client3 = new MockHttpClient($twitterResponse3, 'https://api.twitter.com/2/oauth2/token');

        $bearerToken = new TwitterBearerToken();

        $twitter = new TwitterClient($client1, $bearerToken);
        $refreshToken1 = $bearerToken->getRefreshToken();
        $accesToken1 = $bearerToken->getBearerToken();

        $twitter2 = new TwitterClient($client2, $bearerToken);
        $response2 = $twitter2->postApiRequest($post);

        $expirationDate = new \DateTime('-2 hour');
        $bearerToken->setBearerToken('testBearerToken', $expirationDate);
        $twitter3 = new TwitterClient($client3, $bearerToken);
        $response3 = $twitter3->postApiRequest($post);
        $refreshToken3 = $bearerToken->getRefreshToken();
        $accesToken3 = $bearerToken->getBearerToken();



        $this->assertEquals('refresh_token', $refreshToken1);
        $this->assertEquals('access_token', $accesToken1);

        $this->assertTrue($response2->success);

        $this->assertEquals('refresh_token3', $refreshToken3);
        $this->assertEquals('access_token3', $accesToken3);
        $this->assertTrue($response3->success);
    }

    public function testBadRequestException(): void
    {
        $this->expectException(BadRequestException::class);
        $this->expectExceptionCode(400);
        $this->expectExceptionMessage("Invalid request");
        $twitterResponse1 = [
            new MockResponse(
                file_get_contents(CurrentWorkDirPath::getPath() .
                 '/tests/unit/ressources/TwitterBadRequestResponse.json'),
                ['http_code' => 400]
            ),
        ];
        $client1 = new MockHttpClient($twitterResponse1, 'https://api.twitter.com/2/oauth2/token');
        $bearerToken = new TwitterBearerToken();
        $twitter = new TwitterClient($client1, $bearerToken);
    }

    public function testUnauthorizedException(): void
    {
        $this->expectException(UnauthorizedException::class);
        $this->expectExceptionMessage("Unauthorized");

        $post = (new PostFactory())->buildPostFromRequest($this->request);

        $twitterResponse1 = [
            new MockResponse(
                file_get_contents(CurrentWorkDirPath::getPath() .
                 '/tests/unit/ressources/TwitterResponseRefreshFirst.json'),
                ['http_code' => 200]
            ),
            new MockResponse(
                file_get_contents(CurrentWorkDirPath::getPath() .
                 '/tests/unit/ressources/TwitterBadRequestResponse.json'),
                ['http_code' => 401]
            ),
        ];
        $client1 = new MockHttpClient($twitterResponse1, 'https://api.twitter.com/2/oauth2/token');
        $bearerToken = new TwitterBearerToken();
        $twitter = new TwitterClient($client1, $bearerToken);
        $response = $twitter->postApiRequest($post);
    }
}
