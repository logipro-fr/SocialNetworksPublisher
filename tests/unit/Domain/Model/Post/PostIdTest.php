<?php

namespace SocialNetworksPublisher\Tests\Domain\Model\Post;

use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Domain\Model\Post\PostId;

class PostIdTest extends TestCase
{
    public function testIdentify(): void
    {
        $id1 = new PostId();
        $id2 = new PostId();
        $this->assertIsString($id1->__toString());
        $this->assertStringStartsWith("pos_", $id1);
        $this->assertFalse($id1->equals($id2));
    }

    public function testIdentify2(): void
    {
        $id1 = new PostId();
        $this->assertTrue($id1->equals($id1));
    }

    public function testTostring(): void
    {
        $id1 = new PostId("pos_id");
        $this->assertEquals("pos_id", $id1);
    }

    public function testIdIsntNull(): void
    {
        $id1 = new PostId();
        $this->assertNotEquals(null, $id1);
    }
}
