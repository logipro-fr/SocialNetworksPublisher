<?php

namespace SocialNetworksPublisher\Test\Domain\Model\Page;

use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Domain\Model\Page\PostId;

class PostIdTest extends TestCase
{
    public function testCreatePostId(): void
    {
        $sut = new PostId();
        $this->assertInstanceOf(PostId::class, $sut);
        $this->assertStringStartsWith(PostId::PREFIX, $sut);
    }

    public function testCreateCustomPostId(): void
    {
        $sut = new PostId("my_custom_id");
        $this->assertInstanceOf(PostId::class, $sut);
        $this->assertEquals("my_custom_id", $sut);
    }

    public function testEquality(): void
    {
        $postId1 = new PostId("one");
        $postId2 = new PostId("two");
        $postId3 = new PostId("two");

        $this->assertFalse($postId1->equals($postId2));
        $this->assertTrue($postId3->equals($postId2));
    }

    public function testUniquence(): void
    {
        $postId1 = new PostId();
        $postId2 = new PostId();

        $this->assertFalse($postId1->equals($postId2));
    }

    public function testToString(): void
    {
        $this->assertEquals("customId", new PostId("customId"));
    }
}
