<?php

namespace SocialNetworksPublisher\Tests\Infrastructure\Persistence\Doctrine\Types;

use Doctrine\DBAL\Platforms\SqlitePlatform;
use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Domain\Model\Page\PostStatus;
use SocialNetworksPublisher\Infrastructure\Persistence\Doctrine\Types\PostStatusType;

class PostStatusTypeTest extends TestCase
{
    public function testGetName(): void
    {
        $this->assertEquals('post_status', (new PostStatusType())->getName());
    }

    public function testConvertValue(): void
    {
        $type = new PostStatusType();
        $status = PostStatus::READY;
        $dbValue = $type->convertToDatabaseValue($status, new SqlitePlatform());
        $this->assertIsString($dbValue);
        $phpValue = $type->convertToPHPValue($dbValue, new SqlitePlatform());
        $this->assertEquals($status, $phpValue);
    }
}
