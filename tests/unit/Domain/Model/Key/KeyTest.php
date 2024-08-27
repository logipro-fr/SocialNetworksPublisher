<?php

namespace SocialNetworksPublisher\Tests\Domain\Model\Key;

use DateInterval;
use PHPUnit\Framework\TestCase;
use Safe\DateTimeImmutable;
use SocialNetworksPublisher\Domain\Model\Key\Identity;
use SocialNetworksPublisher\Domain\Model\Key\Key;
use SocialNetworksPublisher\Domain\Model\Key\KeyId;
use SocialNetworksPublisher\Domain\Model\Key\TwitterKeyData;
use SocialNetworksPublisher\Domain\Model\Page\PageId;
use SocialNetworksPublisher\Domain\Model\Shared\SocialNetworks;

class KeyTest extends TestCase
{
    public function testCreateKey(): void
    {
        $sut = new Key(
            new KeyId("key_id"),
            SocialNetworks::Twitter,
            (new DateTimeImmutable())->add(new DateInterval('PT2H')),
            $keyData = new TwitterKeyData("bearer", "refresh"),
            new Identity("page_id")
        );

        $this->assertEquals("key_id", $sut->getKeyId());
        $this->assertEquals($keyData, $sut->getKeyData());
        $this->assertEquals(SocialNetworks::Twitter, $sut->getSocialNetwork());
        $this->assertEquals("page_id", $sut->getValue());
    }

    public function testExpirationTimeIsSetCorrectly(): void
    {
        $currentDateTime = new DateTimeImmutable();
        $expectedExpirationTime = $currentDateTime->add(new DateInterval('PT2H'));
        $keyData = new TwitterKeyData("bearer", "refresh");

        $sut = new Key(
            new KeyId(),
            SocialNetworks::Twitter,
            $expectedExpirationTime,
            $keyData,
            new Identity("page_id")

        );

        $this->assertEquals(
            $expectedExpirationTime,
            $sut->getExpirationDate(),
        );
    }

    public function setExpirationTime(): void
    {
        $currentDateTime = new DateTimeImmutable();
        $keyData = new TwitterKeyData("bearer", "refresh");
        $sut = new Key(
            new KeyId(),
            SocialNetworks::Twitter,
            $currentDateTime,
            $keyData,
            new Identity("page_id")

        );

        $sut->setExpirationDateTime($currentDateTime->add(new DateInterval('PT2H')));

        $this->assertNotEquals($currentDateTime, $sut->getExpirationDate());
    }

    public function testSetPageId(): void
    {
        $currentDateTime = new DateTimeImmutable();
        $keyData = new TwitterKeyData("bearer", "refresh");
        $sut = new Key(
            new KeyId(),
            SocialNetworks::Twitter,
            $currentDateTime,
            $keyData,
            new Identity("page_id")

        );
        $pageId = new PageId("pageId");

        $sut->setIdentity($pageId);

        $this->assertEquals("pageId", $sut->getValue());
    }
}
