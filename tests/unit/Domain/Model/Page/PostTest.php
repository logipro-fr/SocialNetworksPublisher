<?php

namespace SocialNetworksPublisher\Test\Domain\Model\Page;

use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Domain\Model\Page\Post;
use SocialNetworksPublisher\Domain\Model\Page\PostStatus;

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
