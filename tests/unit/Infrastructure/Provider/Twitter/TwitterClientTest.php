<?php

namespace SocialNetworksPublisher\Tests\Infrastructure\Provider\Twitter;

use DateTime;
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
    private const BEARER_PATH = "/var/test_TwitterBearerToken.txt";
    private const REFRESH_PATH = "/var/test_TwitterRefreshToken.txt";
    private const EXPIRATION_PATH = "/var/test_TwitterTokenExpiration.txt";
    private const TEXT_CONTENT = "Hello world !";
    private PublishPostRequest $request;

    protected function setUp(): void
    {
        $this->cleanup();
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
        $post = (new PostFactory())->buildPostFromRequest($this->request);
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
        $twitterResponse2 = [
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
        $tokenBearerClient = new MockHttpClient($tokenBearer, 'https://api.twitter.com/2/oauth2/token');
        $TwitterClient = new MockHttpClient($twitterResponse2, 'https://api.twitter.com/2/tweets');
        $token = new TwitterBearerToken(
            $tokenBearerClient,
            self::BEARER_PATH,
            self::REFRESH_PATH,
            self::EXPIRATION_PATH
        );

        $sut = new TwitterClient($TwitterClient, $token);
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
}
