<?php

namespace SocialNetworksPublisher\Tests\Infrastructure\Persistence\Post;

use SocialNetworksPublisher\Infrastructure\Persistence\Post\PostRepositoryInMemory;

class PostRepositoryInMemoryTest extends PostRepositoryTestBase
{
    protected function setUp(): void
    {
        $this->postRepository = new PostRepositoryInMemory();
    }
}
