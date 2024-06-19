<?php

namespace SocialNetworksPublisher\Tests\Infrastructure\Persistence\Doctrine\Types;

use Doctrine\DBAL\Platforms\MariaDBPlatform;
use Doctrine\DBAL\Platforms\SqlitePlatform;
use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Domain\Model\Post\Author;
use SocialNetworksPublisher\Infrastructure\Persistence\Doctrine\Types\AuthorType;

class AuthorTypeTest extends TestCase
{
    public function testGetName(): void
    {
        $this->assertEquals('author', (new AuthorType())->getName());
    }

    public function testConvertValue(): void
    {
        $type = new AuthorType();
        $dbValue = $type->convertToDatabaseValue(
            $author = new Author("123456"),
            new SqlitePlatform()
        );
        $this->assertIsString($dbValue);
        $phpValue = $type->convertToPHPValue($dbValue, new SqlitePlatform());
        $this->assertEquals($author, $phpValue);
    }
    public function testSqlDeclaration(): void
    {
        $type = new AuthorType();
        $platform = new SqlitePlatform();

        $sqlDeclaration = $type->getSQLDeclaration([], $platform);

        $this->assertEquals("text", $sqlDeclaration);
    }
}
