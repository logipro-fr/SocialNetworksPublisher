<?php

namespace SocialNetworks\Tests\Domain;

use PHPUnit\Framework\TestCase;
use SocialNetworks\Domain\PageId;

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
        $id1 = new PageId("pst_id");
        $this->assertEquals("pst_id", $id1);
    }

    public function testIdIsntNull(): void
    {
        $id1 = new PageId();
        $this->assertNotEquals(null, $id1);
    }
}
