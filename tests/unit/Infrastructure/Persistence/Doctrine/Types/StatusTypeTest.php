<?php

namespace SocialNetworksPublisher\Tests\Infrastructure\Persistence\Doctrine\Types;

use Doctrine\DBAL\Platforms\SqlitePlatform;
use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Domain\Model\Post\Status;
use SocialNetworksPublisher\Infrastructure\Persistence\Doctrine\Types\StatusType;

class StatusTypeTest extends TestCase
{
    public function testGetName(): void
    {
        $this->assertEquals('status', (new StatusType())->getName());
    }

    public function testConvertValue(): void
    {
        $type = new StatusType();
         $status = Status::DRAFT;
        $dbValue = $type->convertToDatabaseValue($status, new SqlitePlatform());
         $this->assertIsString($dbValue);
         $phpValue = $type->convertToPHPValue($dbValue, new SqlitePlatform());
         $this->assertEquals($status, $phpValue);
     }
}
