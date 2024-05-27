<?php

namespace SocialNetworksPublisher\Tests\Infrastructure\Persistence\Doctrine\Types;

use Doctrine\DBAL\Platforms\SqlitePlatform;
use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Domain\Model\Post\Page;
use SocialNetworksPublisher\Infrastructure\Persistence\Doctrine\Types\PageType;

class PageTypeTest extends TestCase
{
    public function testGetName(): void
    {
        $this->assertEquals('page', (new PageType())->getName());
    }

    public function testConvertValue(): void
    {
        $type = new PageType();
        $dbValue = $type->convertToDatabaseValue(
            $page = new Page("facebook", "123456"),
            new SqlitePlatform()
        );
        $this->assertIsString($dbValue);
        $phpValue = $type->convertToPHPValue(
            $dbValue,
            new SqlitePlatform()
        );
        $this->assertEquals($page, $phpValue);
    }
    public function testSqlDeclaration(): void
    {
        $this->assertEquals('text', (new PageType())->getSQLDeclaration([], new SqlitePlatform()));
    }
}
