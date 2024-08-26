<?php

namespace SocialNetworksPublisher\Tests\Infrastructure\Persistence\Doctrine\Types;

use Doctrine\DBAL\Platforms\SqlitePlatform;
use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Domain\Model\Page\PageId;
use SocialNetworksPublisher\Domain\Model\Page\PostId;
use SocialNetworksPublisher\Infrastructure\Persistence\Doctrine\Types\PostIdType;

class PostIdTypeTest extends TestCase
{
    public function testGetName(): void
    {
        $this->assertEquals('post_id', (new PostIdType())->getName());
    }

    public function testConvertToPHPValue(): void
    {
        $type = new PostIdType();
        $id = $type->convertToPHPValue("pos_", new SqlitePlatform());
        $this->assertEquals(true, $id instanceof PostId);
    }

    public function testConvertToDatabaseValue(): void
    {
        $type = new PostIdType();
        $dbValue = $type->convertToDatabaseValue($id = new PostId(), new SqlitePlatform());
        $this->assertEquals($id->__toString(), $dbValue);
    }
}
