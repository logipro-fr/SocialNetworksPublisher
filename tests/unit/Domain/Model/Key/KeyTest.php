<?php

namespace SocialNetworksPublisher\Tests\Domain\Model\Key;

use DateInterval;
use PHPUnit\Framework\TestCase;
use Safe\DateTimeImmutable;
use SocialNetworksPublisher\Domain\Model\Key\Key;
use SocialNetworksPublisher\Domain\Model\Key\KeyId;
use SocialNetworksPublisher\Domain\Model\Key\TwitterKeyData;
use SocialNetworksPublisher\Domain\Model\Page\Page;
use SocialNetworksPublisher\Domain\Model\Page\PageId;
use SocialNetworksPublisher\Domain\Model\Page\PageName;
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
        );

        $this->assertEquals("key_id", $sut->getKeyId());
        $this->assertEquals($keyData, $sut->getKeyData());
        $this->assertEquals(SocialNetworks::Twitter, $sut->getSocialNetwork());
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
            $keyData
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
            $keyData
        );

        $sut->setExpirationDateTime($currentDateTime->add(new DateInterval('PT2H')));

        $this->assertNotEquals($currentDateTime, $sut->getExpirationDate());
    }

    public function testKeyPagesAdd(): void
    {
        $currentDateTime = new DateTimeImmutable();
        $keyData = new TwitterKeyData("bearer", "refresh");
        $sut = new Key(
            new KeyId(),
            SocialNetworks::Twitter,
            $currentDateTime,
            $keyData
        );
        $pageId = new PageId("pageId");

        $sut->addPageId($pageId);

        $this->assertEquals("pageId", $sut->getPageIds()[0]);
    }
}
