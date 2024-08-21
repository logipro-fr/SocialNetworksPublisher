<?php

namespace SocialNetworksPublisher\Domain\Model\Page;

use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Domain\Model\Page\Post;

class PostTest extends TestCase
{
    public function testCreatePost(): void
    {
        $sut = new Post(
            "Content test",
            PostStatus::READY
        );

        $this->assertEquals("Content test", $sut->getContent());
        $this->assertEquals(PostStatus::READY, $sut->getStatus());
    }
}
