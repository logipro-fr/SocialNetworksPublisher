<?php

namespace SocialNetworksPublisher\Tests\Infrastructure\Persistence\Doctrine\Types;

use Doctrine\DBAL\Platforms\SqlitePlatform;
use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Domain\Model\Post\Content;
use SocialNetworksPublisher\Infrastructure\Persistence\Doctrine\Types\ContentType;

class ContentTypeTest extends TestCase
{
    public function testGetName(): void
    {
        $this->assertEquals("content", (new ContentType())->getName());
    }

    public function testConvertValue(): void
    {
        $type = new ContentType();
        $dbValue = $type->convertToDatabaseValue(
            $content = new Content("ceci est un content de test"),
            new SqlitePlatform()
        );

        $this->assertIsString($dbValue);
        $phpValue = $type->convertToPHPValue($dbValue, new SqlitePlatform());
        $this->assertEquals($content, $phpValue);
    }
    public function testSqlDeclaration(): void {
        $this->assertEquals('text', (new ContentType())->getSQLDeclaration([], new SqlitePlatform()));
    }
}
