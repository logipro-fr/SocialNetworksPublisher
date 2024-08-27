<?php

namespace SocialNetworksPublisher\Tests\Infrastructure\Persistence\Key;

use DoctrineTestingTools\DoctrineRepositoryTesterTrait;
use SocialNetworksPublisher\Infrastructure\Persistence\Key\KeyRepositoryDoctrine;

class KeyRepositoryDoctrineTest extends KeyRepositoryTestBase
{
    use DoctrineRepositoryTesterTrait;

    protected function setUp(): void
    {
        $this->initDoctrineTester();
        $this->clearTables(["keys"]);
        $this->keys = new KeyRepositoryDoctrine($this->getEntityManager());
    }
}
