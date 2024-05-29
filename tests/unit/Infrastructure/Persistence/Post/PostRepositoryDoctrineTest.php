<?php

namespace SocialNetworksPublisher\Tests\Infrastructure\Persistence\Post;

use DoctrineTestingTools\DoctrineRepositoryTesterTrait;
use SocialNetworksPublisher\Infrastructure\Persistence\Post\PostRepositoryDoctrine;

class PostRepositoryDoctrineTest extends PostRepositoryTestBase
{
    use DoctrineRepositoryTesterTrait;

    protected function initialize(): void
    {
        $this->initDoctrineTester();
        $this->clearTables(['posts']);
        $this->postRepository = new PostRepositoryDoctrine($this->getEntityManager());
        //$this->postRepository = new FlushingMapRepositoryDoctrine($this->getEntityManager());
    }

    public function testFlush(): void
    {
        $this->initDoctrineTester();
        $postRepository = new PostRepositoryDoctrine($this->getEntityManager());
        $postRepository->flush();
        $this->assertTrue(true);
    }
}
