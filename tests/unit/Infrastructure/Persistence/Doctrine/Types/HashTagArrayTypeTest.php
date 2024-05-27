<?php

namespace SocialNetworksPublisher\Tests\Infrastructure\Persistence\Doctrine\Types;

use Doctrine\DBAL\Platforms\SqlitePlatform;
use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Domain\Model\Post\HashTagArray;
use SocialNetworksPublisher\Domain\Model\Post\HashTagArrayFactory;
use SocialNetworksPublisher\Infrastructure\Persistence\Doctrine\Types\HashTagArrayType;

class HashTagArrayTypeTest extends TestCase
{
    public function testGetName(): void
    {
        $this->assertEquals('hash_tag_array', (new HashTagArrayType())->getName());
    }

    public function testConvertValue(): void
    {
        $type = new HashTagArrayType();

        $dbValue = $type->convertToDatabaseValue(
            $hashTags = $this->generateHashTagArray(),
            new SqlitePlatform(),
        );
        $phpValue = $type->convertToPHPValue(
            $dbValue,
            new SqlitePlatform(),
        );

        $this->assertIsString($dbValue);
        $this->assertEquals($hashTags, $phpValue);
    }

    public function generateHashTagArray(): HashTagArray
    {
        $factory = new HashTagArrayFactory();
        $hashTags  = $factory->buildHashTagArrayFromSentence("1, #2, 3, , ", ", ");
        return $hashTags;
    }

    public function testSqlDeclaration(): void
    {
        $this->assertEquals('text', (new HashTagArrayType())->getSQLDeclaration([], new SqlitePlatform()));
    }
}
