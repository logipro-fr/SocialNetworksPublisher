<?php

namespace SocialNetworksPublisher\Tests\Infrastructure\Persistence\Page;

use SocialNetworksPublisher\Infrastructure\Persistence\Page\PageRepositoryInMemory;

class PageRepositoryInMemoryTest extends PageRepositoryTestBase
{
    protected function setUp(): void
    {
        $this->pages = new PageRepositoryInMemory();
    }
}
