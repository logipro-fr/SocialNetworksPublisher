<?php

namespace SocialNetworksPublisher\Tests\Infrastructure\Provider\Twitter;

use DateTime;
use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Infrastructure\Provider\Exceptions\BadRequestException;
use SocialNetworksPublisher\Infrastructure\Provider\Exceptions\TokenExpirationTimeFileException;
use SocialNetworksPublisher\Infrastructure\Provider\Twitter\TwitterBearerToken;
use SocialNetworksPublisher\Infrastructure\Shared\CurrentWorkDirPath;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

use function Safe\file_get_contents;
use function Safe\file_put_contents;

class TwitterBearerTokenTest extends TestCase
{
    private const BEARER_PATH = "/var/test_TwitterBearerToken.txt";
    private const REFRESH_PATH = "/var/test_TwitterRefreshToken.txt";
    private const EXPIRATION_PATH = "/var/test_TwitterTokenExpiration.txt";
    private MockHttpClient $client;

    protected function setUp(): void
    {
        $this->cleanup();
        $this->client = $this->createMockHttpClient(
            "TwitterResponseRefreshFirst.json",
            200,
            "https://api.twitter.com/2/oauth2/token"
        );
    }

    protected function tearDown(): void
    {
        $this->cleanup();
    }

    private function cleanup(): void
    {
        @unlink(CurrentWorkDirPath::getPath() . self::BEARER_PATH);
        @unlink(CurrentWorkDirPath::getPath() . self::REFRESH_PATH);
        @unlink(CurrentWorkDirPath::getPath() . self::EXPIRATION_PATH);
    }

    public function testGetBearerToken(): void
    {
        $sut = $this->createBearerToken($this->client);

        $this->assertEquals('access_token', $sut->getBearerToken());
    }

    public function testGetRefreshToken(): void
    {
        $sut = $this->createBearerToken($this->client);

        $this->assertEquals('refresh_token', $sut->getRefreshToken());
    }

    public function testGetExpirationDate(): void
    {
        $sut = $this->createBearerToken($this->client);
        $expirationDate = (new DateTime());
        $expectedDate = DateTime::createFromFormat('Y-m-d\TH:i:sP', $expirationDate->format('Y-m-d\TH:i:sP'));
        file_put_contents(
            CurrentWorkDirPath::getPath() . self::EXPIRATION_PATH,
            $expirationDate->format(DateTime::ATOM)
        );

        $this->assertEquals($expectedDate, $sut->getExpirationDate());
    }

    public function testNeedsRefresh(): void
    {
        file_put_contents(CurrentWorkDirPath::getPath() . self::REFRESH_PATH, 'test');
        $expirationDate = new \DateTime('-2 hour');
        file_put_contents(
            CurrentWorkDirPath::getPath() . self::EXPIRATION_PATH,
            $expirationDate->format(DateTime::ATOM)
        );
        $sut = $this->createBearerToken($this->client);

        $this->assertEquals('refresh_token', $sut->getRefreshToken());

        $expirationDate = new \DateTime('-1 hour');
        file_put_contents(CurrentWorkDirPath::getPath() . self::REFRESH_PATH, 'Dont change');
        file_put_contents(
            CurrentWorkDirPath::getPath() . self::EXPIRATION_PATH,
            $expirationDate->format(DateTime::ATOM)
        );
        $client = new MockHttpClient(new MockResponse(), "https://api.twitter.com/2/oauth2/token");
        $sut = new TwitterBearerToken($client, self::BEARER_PATH, self::REFRESH_PATH, self::EXPIRATION_PATH);

        $this->assertEquals('Dont change', $sut->getRefreshToken());
    }

    public function testExpirationFileException(): void
    {
        $this->expectException(TokenExpirationTimeFileException::class);
        $sut = new TwitterBearerToken($this->client, self::BEARER_PATH, self::REFRESH_PATH, self::EXPIRATION_PATH);
        unlink(CurrentWorkDirPath::getPath() . self::EXPIRATION_PATH);
        $sut->needsRefresh();
    }

    public function testConstructWithGetEnvWhenNoFileSetup(): void
    {
        $sut = $this->createBearerToken($this->client);
        $expectedDate = DateTime::createFromFormat(
            'Y-m-d\TH:i:sP',
            (new DateTime("+2 hours"))->format(DateTime::ATOM)
        );
        $this->assertFileExists(CurrentWorkDirPath::getPath() . self::REFRESH_PATH);
        $this->assertFileExists(CurrentWorkDirPath::getPath() . self::BEARER_PATH);
        $this->assertFileExists(CurrentWorkDirPath::getPath() . self::EXPIRATION_PATH);
        $this->assertEquals("refresh_token", $sut->getRefreshToken());
        $this->assertEquals("access_token", $sut->getBearerToken());
        $this->assertEquals($expectedDate, $sut->getExpirationDate());
    }

    public function testInvalidRequestException(): void
    {
        $this->expectException(BadRequestException::class);
        $this->expectExceptionCode(400);
        $this->expectExceptionMessage("Invalid request");

        $client = $this->createMockHttpClient(
            'TwitterBadRequestResponse.json',
            400,
            'https://api.twitter.com/2/oauth2/token'
        );
        $this->createBearerToken($client);
    }

    private function createMockHttpClient(string $filename, int $code, string $url): MockHttpClient
    {
        $responses = [
            new MockResponse(
                file_get_contents(CurrentWorkDirPath::getPath() . "/tests/unit/ressources/" . $filename),
                ['http_code' => $code]
            ),
        ];
        return new MockHttpClient($responses, $url);
    }

    private function createBearerToken(MockHttpClient $client): TwitterBearerToken
    {
        return new TwitterBearerToken(
            $client,
            self::BEARER_PATH,
            self::REFRESH_PATH,
            self::EXPIRATION_PATH
        );
    }
}
