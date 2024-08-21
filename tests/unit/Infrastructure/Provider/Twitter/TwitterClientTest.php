<?php

namespace SocialNetworksPublisher\Tests\Infrastructure\Provider\Twitter;

use DateTime;
use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Application\Service\PublishPost\PostFactory;
use SocialNetworksPublisher\Application\Service\PublishPost\PublishPostRequest;
use SocialNetworksPublisher\Domain\Model\Page\Post;
use SocialNetworksPublisher\Domain\Model\Page\PostId;
use SocialNetworksPublisher\Domain\Model\Page\PostStatus;
use SocialNetworksPublisher\Infrastructure\Provider\Exceptions\BadRequestException;
use SocialNetworksPublisher\Infrastructure\Provider\Exceptions\DuplicatePostException;
use SocialNetworksPublisher\Infrastructure\Provider\Exceptions\UnauthorizedException;
use SocialNetworksPublisher\Infrastructure\Provider\Twitter\TwitterBearerToken;
use SocialNetworksPublisher\Infrastructure\Provider\Twitter\TwitterClient;
use SocialNetworksPublisher\Infrastructure\Shared\CurrentWorkDirPath;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

use function Safe\file_get_contents;

class TwitterClientTest extends TestCase
{
    private const BEARER_PATH = "/var/test_TwitterBearerToken.txt";
    private const REFRESH_PATH = "/var/test_TwitterRefreshToken.txt";
    private const EXPIRATION_PATH = "/var/test_TwitterTokenExpiration.txt";
    private const TEXT_CONTENT = "Hello world !";

    protected function setUp(): void
    {
        $this->cleanup();
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

    public function testTwitterRequestMecanismWithRefresh(): void
    {
        $post = new Post(
            self::TEXT_CONTENT,
            PostStatus::READY
        );
        $token = $this->prepareTwitterMockBearerToken();
        $twitterMockClient = $this->prepareTwitterMockClient();
        $sut = new TwitterClient($twitterMockClient, $token);
        $refreshToken1 = $token->getRefreshToken();
        $accessToken1 = $token->getBearerToken();

        $expirationDate = new \DateTime('-2 hour');
        file_put_contents(
            CurrentWorkDirPath::getPath() . self::EXPIRATION_PATH,
            $expirationDate->format(DateTime::ATOM)
        );
        $postResponse = $sut->postApiRequest($post);

        $postResponse2 = $sut->postApiRequest($post);
        $refreshToken2 = $token->getRefreshToken();
        $accessToken2 = $token->getBearerToken();

        $this->assertTrue($postResponse->success);
        $this->assertNotEquals($refreshToken1, $refreshToken2);
        $this->assertNotEquals($accessToken1, $accessToken2);
        $this->assertTrue($postResponse2->success);

        $this->expectException(UnauthorizedException::class);
        $this->expectExceptionMessage("Unauthorized");

        $sut->postApiRequest($post);
    }

    public function testDuplicateContentException(): void
    {
        $post = new Post(
            self::TEXT_CONTENT,
            PostStatus::READY
        );        $twitterResponse = [
            new MockResponse(
                file_get_contents(CurrentWorkDirPath::getPath() .
                 '/tests/unit/ressources/TwitterResponseTweet.json'),
                ['http_code' => 200]
            ),
            new MockResponse(
                file_get_contents(CurrentWorkDirPath::getPath() .
                 '/tests/unit/ressources/TwitterResponseDuplicateTweet.json'),
                ['http_code' => 403]
            ),
        ];
        $twitterMockClient = new MockHttpClient($twitterResponse, 'https://api.twitter.com/2/tweets');

        $token = $this->prepareTwitterMockBearerToken();
        $sut = new TwitterClient($twitterMockClient, $token);

        $this->expectException(DuplicatePostException::class);

        //First Post
        $sut->postApiRequest($post);
        //Second same post who throwing the exception
        $sut->postApiRequest($post);
    }

    public function testBadRequestException(): void
    {
        $post = new Post(
            self::TEXT_CONTENT,
            PostStatus::READY
        );        $twitterResponse = [
            new MockResponse(
                file_get_contents(CurrentWorkDirPath::getPath() .
                 '/tests/unit/ressources/TwitterResponseTweet.json'),
                ['http_code' => 426]
            )
        ];

        $twitterMockClient = new MockHttpClient($twitterResponse, 'https://api.twitter.com/2/tweets');

        $token = $this->prepareTwitterMockBearerToken();
        $sut = new TwitterClient($twitterMockClient, $token);

        $this->expectException(BadRequestException::class);

        $sut->postApiRequest($post);
    }

    private function prepareTwitterMockClient(): MockHttpClient
    {
        $twitterResponse = [
            new MockResponse(
                file_get_contents(CurrentWorkDirPath::getPath() .
                 '/tests/unit/ressources/TwitterResponseTweet.json'),
                ['http_code' => 200]
            ),
            new MockResponse(
                file_get_contents(CurrentWorkDirPath::getPath() .
                 '/tests/unit/ressources/TwitterResponseTweet.json'),
                ['http_code' => 200]
            ),
            new MockResponse(
                file_get_contents(CurrentWorkDirPath::getPath() .
                 '/tests/unit/ressources/TwitterBadRequestResponse.json'),
                ['http_code' => 401]
            ),
        ];
        return new MockHttpClient($twitterResponse, 'https://api.twitter.com/2/tweets');
    }

    private function prepareTwitterMockBearerToken(): TwitterBearerToken
    {
        $tokenBearer = [
            new MockResponse(
                file_get_contents(CurrentWorkDirPath::getPath() .
                 '/tests/unit/ressources/TwitterResponseRefreshFirst.json'),
                ['http_code' => 200]
            ),
            new MockResponse(
                file_get_contents(CurrentWorkDirPath::getPath() .
                 '/tests/unit/ressources/TwitterResponseRefresh.json'),
                ['http_code' => 200]
            ),
        ];

        $tokenBearerClient = new MockHttpClient($tokenBearer, 'https://api.twitter.com/2/oauth2/token');
        return new TwitterBearerToken(
            $tokenBearerClient,
            self::BEARER_PATH,
            self::REFRESH_PATH,
            self::EXPIRATION_PATH
        );
    }
}
