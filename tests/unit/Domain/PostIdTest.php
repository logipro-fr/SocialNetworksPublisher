<?php

namespace SocialNetworksPublisher\Tests\Domain;

use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Domain\PostId;

class PostIdTest extends TestCase
{
    public function testIdentify(): void
    {
        $id1 = new PostId();
        $id2 = new PostId();

        $this->assertFalse($id1->equals($id2));
    }

    public function testIdentify2(): void
    {
        $id1 = new PostId();
        $this->assertTrue($id1->equals($id1));
    }

    public function testTostring(): void
    {
        $id1 = new PostId("pst_id");
        $this->assertEquals("pst_id", $id1);
    }

    public function testIdIsntNull(): void
    {
        $id1 = new PostId();
        $this->assertNotEquals(null, $id1);
    }
}
