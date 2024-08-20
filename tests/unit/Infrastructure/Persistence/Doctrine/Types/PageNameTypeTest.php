<?php

namespace SocialNetworksPublisher\Tests\Infrastructure\Persistence\Doctrine\Types;

use Doctrine\DBAL\Platforms\SqlitePlatform;
use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Domain\Model\Page\PageName;
use SocialNetworksPublisher\Infrastructure\Persistence\Doctrine\Types\ContentType;
use SocialNetworksPublisher\Infrastructure\Persistence\Doctrine\Types\PageNameType;

class PageNameTypeTest extends TestCase
{
    public function testGetName(): void
    {
        $this->assertEquals("page_name", (new PageNameType())->getName());
    }

    public function testConvertValue(): void
    {
        $type = new PageNameType();
        $dbValue = $type->convertToDatabaseValue(
            $content = new PageName("page_name"),
            new SqlitePlatform()
        );

        $this->assertIsString($dbValue);
        $phpValue = $type->convertToPHPValue($dbValue, new SqlitePlatform());
        $this->assertEquals($content, $phpValue);
    }
    public function testSqlDeclaration(): void
    {
        $this->assertEquals('text', (new PageNameType())->getSQLDeclaration([], new SqlitePlatform()));
    }
}
