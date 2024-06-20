<?php

namespace SocialNetworksPublisher\Tests\Infrastructure\Persistence\Post;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use DoctrineTestingTools\DoctrineRepositoryTesterTrait;
use PhpParser\Node\Expr\Cast\Object_;
use SocialNetworksPublisher\Infrastructure\Persistence\Post\PostRepositoryDoctrine;

class PostRepositoryDoctrineTest extends PostRepositoryTestBase
{
    use DoctrineRepositoryTesterTrait;

    protected function setUp(): void
    {
        $this->initDoctrineTester();
        $this->clearTables(['posts']);
        $this->postRepository = new PostRepositoryDoctrine($this->getEntityManager());
    }

    public function testFlush(): void
    {
        $metadata = $this->createMock(ClassMetadata::class);
        $metadata->name =  PostRepositoryDoctrineTest::class;

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager
            ->expects($this->once())
            ->method('flush');

        $entityManager
            ->method('getClassMetadata')
            ->willReturn($metadata);

        $sut = new PostRepositoryDoctrine($entityManager);
        $sut->flush();
    }
}
