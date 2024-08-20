<?php

namespace SocialNetworksPublisher\Domain\Model\Page;

use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Domain\Model\Page\PageId;

class PageIdTest extends TestCase
{
    public function testCreatePageId(): void
    {
        $sut = new PageId();
        $this->assertInstanceOf(PageId::class, $sut);
        $this->assertIsString($sut->getId());
    }

    public function testCreateCustomPageId(): void
    {
        $sut = new PageId("my_custom_id");
        $this->assertInstanceOf(PageId::class, $sut);
        $this->assertEquals("my_custom_id", $sut->getId());
    }

    public function testEquality(): void
    {
        $pageId1 = new PageId("one");
        $pageId2 = new PageId("two");
        $pageId3 = new PageId("two");

        $this->assertFalse($pageId1->equals($pageId2));
        $this->assertTrue($pageId3->equals($pageId2));
    }

    public function testUniquence(): void
    {
        $pageId1 = new PageId();
        $pageId2 = new PageId();

        $this->assertFalse($pageId1->equals($pageId2));
    }

    public function testToString(): void
    {
        $this->assertEquals("customId", new PageId("customId"));
    }
}
