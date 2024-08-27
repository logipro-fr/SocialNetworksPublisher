<?php

namespace SocialNetworksPublisher\Tests\Infrastructure\Persistence\Key;

use SocialNetworksPublisher\Infrastructure\Persistence\Key\KeyRepositoryInMemory;

class KeyRepositoryInMemoryTest extends KeyRepositoryTestBase
{
    protected function setUp(): void
    {
        $this->keys = new KeyRepositoryInMemory();
    }
}
