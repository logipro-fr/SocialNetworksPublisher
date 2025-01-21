<?php

namespace SocialNetworksPublisher\Tests\Domain\Model\Key;

use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Domain\Model\Key\KeyId;

class KeyIdTest extends TestCase
{
    public function testCreateKeyId(): void
    {
        $sut = new KeyId();
        $this->assertInstanceOf(KeyId::class, $sut);
        $this->assertStringStartsWith(KeyId::PREFIX, $sut);
    }

    public function testCreateCustomKeyId(): void
    {
        $sut = new KeyId("my_custom_id");
        $this->assertInstanceOf(KeyId::class, $sut);
        $this->assertEquals("my_custom_id", $sut);
    }

    public function testEquality(): void
    {
        $keyId1 = new KeyId("one");
        $keyId2 = new KeyId("two");
        $keyId3 = new KeyId("two");

        $this->assertFalse($keyId1->equals($keyId2));
        $this->assertTrue($keyId3->equals($keyId2));
    }

    public function testUniquence(): void
    {
        $keyId1 = new KeyId();
        $keyId2 = new KeyId();

        $this->assertFalse($keyId1->equals($keyId2));
    }

    public function testToString(): void
    {
        $this->assertEquals("customId", new KeyId("customId"));
    }
}
