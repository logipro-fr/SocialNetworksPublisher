<?php 
namespace SocialNetworksPublisher\Test\Domain\Model\Page;

use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Domain\Model\Key\AbstractKeyData;
use SocialNetworksPublisher\Domain\Model\Key\Exceptions\KeyDataEmptyBearerTokenException;
use SocialNetworksPublisher\Domain\Model\Key\Exceptions\KeyLinkedInDataEmptyUrnException;
use SocialNetworksPublisher\Domain\Model\Key\Exceptions\KeyTwitterDataEmptyRefreshTokenException;
use SocialNetworksPublisher\Domain\Model\Key\LinkedInKeyData;
use SocialNetworksPublisher\Domain\Model\Key\TwitterKeyData;

class AbstractKeyDataTest extends TestCase {
    public function testCreateTwitterDataTest(): void {
        $sut = new TwitterKeyData("bearer_token", "refresh_token");
        $this->assertInstanceOf(AbstractKeyData::class, $sut);
        $this->assertEquals("bearer_token", $sut->getBearerToken());
        $this->assertEquals("refresh_token", $sut->getRefreshToken());
    }

    public function testTwitterSetData(): void {
        $sut = new TwitterKeyData("bearer_token", "refresh_token");
        $sut->setBearerToken("new_bearer_token");
        $sut->setRefreshToken("new_refresh_token");

        $this->assertEquals("new_bearer_token", $sut->getBearerToken());
        $this->assertEquals("new_refresh_token", $sut->getRefreshToken());

    }

    public function testCreateLinkedInDataTest(): void {
        $sut = new LinkedInKeyData("bearer_token", "urn");
        $this->assertInstanceOf(AbstractKeyData::class, $sut);
        $this->assertEquals("bearer_token", $sut->getBearerToken());
        $this->assertEquals("urn", $sut->getUrn());
    }


    public function testTwitterBearerTokenException(): void {
        $this->expectException(KeyDataEmptyBearerTokenException::class);
        $this->expectExceptionCode(KeyDataEmptyBearerTokenException::ERROR_CODE);
        $this->expectExceptionMessage(sprintf(KeyDataEmptyBearerTokenException::MESSAGE));

        new TwitterKeyData("", "urn");
    }

    public function testLinkedInBearerTokenException(): void {
        $this->expectException(KeyDataEmptyBearerTokenException::class);
        $this->expectExceptionCode(KeyDataEmptyBearerTokenException::ERROR_CODE);
        $this->expectExceptionMessage(sprintf(KeyDataEmptyBearerTokenException::MESSAGE));

        new LinkedInKeyData("", "urn");
    }

    public function testTwitterRefreshTokenException(): void {
        $this->expectException(KeyTwitterDataEmptyRefreshTokenException::class);
        $this->expectExceptionCode(KeyTwitterDataEmptyRefreshTokenException::ERROR_CODE);
        $this->expectExceptionMessage(sprintf(KeyTwitterDataEmptyRefreshTokenException::MESSAGE));

        new TwitterKeyData("token", "");
    }

    public function testLinkedInUrnException(): void {
        $this->expectException(KeyLinkedInDataEmptyUrnException::class);
        $this->expectExceptionCode(KeyLinkedInDataEmptyUrnException::ERROR_CODE);
        $this->expectExceptionMessage(sprintf(KeyLinkedInDataEmptyUrnException::MESSAGE));

        new LinkedInKeyData("token", "");
    }
}