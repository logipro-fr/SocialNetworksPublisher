<?php

namespace SocialNetworksPublisher\Tests\Infrastructure\Persistence\Doctrine\Types;

use Doctrine\DBAL\Platforms\SqlitePlatform;
use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Domain\Model\Shared\SocialNetworks;
use SocialNetworksPublisher\Infrastructure\Persistence\Doctrine\Types\SocialNetworksType;

class SocialNetworksTypeTest extends TestCase
{
    public function testGetName(): void
    {
        $this->assertEquals('social_network', (new SocialNetworksType())->getName());
    }

    public function testConvertValue(): void
    {
        $type = new SocialNetworksType();
         $status = SocialNetworks::Twitter;
        $dbValue = $type->convertToDatabaseValue($status, new SqlitePlatform());
         $this->assertIsString($dbValue);
         $phpValue = $type->convertToPHPValue($dbValue, new SqlitePlatform());
         $this->assertEquals($status, $phpValue);
    }
}
