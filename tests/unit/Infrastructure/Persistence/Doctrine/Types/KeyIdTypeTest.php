<?php

namespace SocialNetworksPublisher\Tests\Infrastructure\Persistence\Doctrine\Types;

use Doctrine\DBAL\Platforms\SqlitePlatform;
use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Domain\Model\Key\KeyId;
use SocialNetworksPublisher\Domain\Model\Page\PageId;
use SocialNetworksPublisher\Infrastructure\Persistence\Doctrine\Types\KeyIdType;

class KeyIdTypeTest extends TestCase
{
    public function testGetName(): void
    {
        $this->assertEquals('key_id', (new KeyIdType())->getName());
    }

    public function testConvertToPHPValue(): void
    {
        $type = new KeyIdType();
        $id = $type->convertToPHPValue("key_", new SqlitePlatform());
        $this->assertEquals(new KeyId("key_"), $id);
    }

    public function testConvertToDatabaseValue(): void
    {
        $type = new KeyIdType();
        $dbValue = $type->convertToDatabaseValue($id = new KeyId(), new SqlitePlatform());
        $this->assertEquals($id->__toString(), $dbValue);
    }
}
