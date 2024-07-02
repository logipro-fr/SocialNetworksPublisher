<?php

namespace SocialNetworksPublisher\Tests\Infrastructure\Provider\Twitter;

use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Infrastructure\Provider\Twitter\TwitterBearerToken;
use SocialNetworksPublisher\Infrastructure\Shared\CurrentWorkDirPath;

use function Safe\file_get_contents;

class TwitterBearerTokenTest extends TestCase
{
    private const BEARER_PATH = "/var/test_TwitterBearerToken.txt";
    private const REFRESH_PATH = "/var/test_TwitterRefreshToken.txt";
    private const EXPIRATION_PATH = "/var/test_TwitterTokenExpiration.txt";

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
        @unlink(CurrentWorkDirPath::getPath() . self::BEARER_PATH);
        @unlink(CurrentWorkDirPath::getPath() . self::REFRESH_PATH);
        @unlink(CurrentWorkDirPath::getPath() . self::EXPIRATION_PATH);
    }

    public function testGetAndSetBearerToken(): void
    {
        $token = new TwitterBearerToken(self::BEARER_PATH, self::REFRESH_PATH, self::EXPIRATION_PATH);
        $expirationDate = new \DateTime('+1 hour');
        $token->setBearerToken('testBearerToken', $expirationDate);

        $this->assertEquals('testBearerToken', file_get_contents(CurrentWorkDirPath::getPath() . self::BEARER_PATH));
        $this->assertEquals($expirationDate->format(\DateTime::ATOM), file_get_contents(CurrentWorkDirPath::getPath() . self::EXPIRATION_PATH));
    }

    public function testGetAndSetRefreshToken(): void
    {
        $token = new TwitterBearerToken(self::BEARER_PATH, self::REFRESH_PATH, self::EXPIRATION_PATH);
        $token->setRefreshToken('testRefreshToken');

        $this->assertEquals('testRefreshToken', file_get_contents(CurrentWorkDirPath::getPath() . self::REFRESH_PATH));
        $this->assertEquals(CurrentWorkDirPath::getPath() . self::REFRESH_PATH, CurrentWorkDirPath::getPath() . $token->getRefreshPath());
    }

    public function testNeedsRefresh(): void
    {
        $token = new TwitterBearerToken(self::BEARER_PATH, self::REFRESH_PATH, self::EXPIRATION_PATH);
        $expirationDate = new \DateTime('-2 hour');
        $token->setBearerToken('testBearerToken', $expirationDate);

        $this->assertTrue($token->needsRefresh());

        $expirationDate = new \DateTime('-1 hour');
        $token->setBearerToken('testBearerToken', $expirationDate);

        $this->assertFalse($token->needsRefresh());
    }
}
