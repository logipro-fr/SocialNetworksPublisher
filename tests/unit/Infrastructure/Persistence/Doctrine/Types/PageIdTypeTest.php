<?php

namespace SocialNetworksPublisher\Tests\Infrastructure\Persistence\Doctrine\Types;

use Doctrine\DBAL\Platforms\SqlitePlatform;
use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Domain\Model\Page\PageId;
use SocialNetworksPublisher\Infrastructure\Persistence\Doctrine\Types\PageIdType;

class PageIdTypeTest extends TestCase
{
    public function testGetName(): void
    {
        $this->assertEquals('page_id', (new PageIdType())->getName());
    }

    public function testConvertToPHPValue(): void
    {
        $type = new PageIdType();
        $id = $type->convertToPHPValue("pag_", new SqlitePlatform());
        $this->assertEquals(new PageId("pag_"), $id);
    }

    public function testConvertToDatabaseValue(): void
    {
        $type = new PageIdType();
        $dbValue = $type->convertToDatabaseValue($id = new PageId(), new SqlitePlatform());
        $this->assertEquals($id->__toString(), $dbValue);
    }
}
