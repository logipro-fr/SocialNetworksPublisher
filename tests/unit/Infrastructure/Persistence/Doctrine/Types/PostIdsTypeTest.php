<?php

namespace SocialNetworksPublisher\Tests\Infrastructure\Persistence\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\SqlitePlatform;
use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Domain\Model\Post\PostId;
use SocialNetworksPublisher\Infrastructure\Persistence\Doctrine\Types\PostIdsType;

class PostIdsTypeTest extends TestCase
{
    private PostIdsType $postIdsType;
    private AbstractPlatform $platform;

    protected function setUp(): void
    {
        $this->postIdsType = new PostIdsType();
        $this->platform = new SqlitePlatform();
    }

    public function testConvertToDatabaseValue()
    {
        $postIds = [
            new PostId('123'),
            new PostId('456'),
            new PostId('789')
        ];

        $expected = '123,456,789';
        $actual = $this->postIdsType->convertToDatabaseValue($postIds, $this->platform);

        $this->assertEquals($expected, $actual);
    }

    public function testConvertToPHPValue()
    {
        $dbValue = '123,456,789';

        $postIds = $this->postIdsType->convertToPHPValue($dbValue, $this->platform);

        $this->assertCount(3, $postIds);
        $this->assertInstanceOf(PostId::class, $postIds[0]);
        $this->assertEquals('123', $postIds[0]->__toString());
        $this->assertEquals('456', $postIds[1]->__toString());
        $this->assertEquals('789', $postIds[2]->__toString());
    }

    public function testGetName()
    {
        $this->assertEquals('post_ids', $this->postIdsType->getName());
    }
}
