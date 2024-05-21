<?php

namespace SocialNetworksPublisher\Tests\Domain\Model\Post;

use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Domain\Model\Post\PageId;

class PageIdTest extends TestCase
{
    public function testIdentify(): void
    {
        $id1 = new PageId();
        $id2 = new PageId();

        $this->assertFalse($id1->equals($id2));
    }

    public function testIdentify2(): void
    {
        $id1 = new PageId();

        $this->assertTrue($id1->equals($id1));
    }

    public function testTostring(): void
    {
        $id1 = new PageId("pag_id");

        $this->assertEquals("pag_id", $id1);
    }

    public function testIdIsntNull(): void
    {
        $id1 = new PageId();
        $this->assertNotEquals(null, $id1);
    }
}
