<?php

namespace SocialNetworksPublisher\Tests\Infrastructure\Persitence;

use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Domain\Model\Post\Author;
use SocialNetworksPublisher\Domain\Model\Post\Content;
use SocialNetworksPublisher\Domain\Model\Post\HashTag;
use SocialNetworksPublisher\Domain\Model\Post\HashTagArray;
use SocialNetworksPublisher\Domain\Model\Post\Page;
use SocialNetworksPublisher\Domain\Model\Post\Post;
use SocialNetworksPublisher\Domain\Model\Post\PostId;
use SocialNetworksPublisher\Domain\Model\Post\Status;
use SocialNetworksPublisher\Infrastructure\Persistence\PostRepositoryInMemory;

class PostRepositoryInMemoryTest extends TestCase
{
    public function testFindById(): void
    {
        $repo = new PostRepositoryInMemory();
        $author = new Author('facebook', '123za45g');
        $content = new Content("Lorem ipsum dolor sit amet, consectetur adipiscing elit.");
        $page = new Page("facebook", "98ad48644");
        $post = new Post($author, $content, new HashTagArray(), $page, Status::READY, new PostId("prime"));
        $post2 = new Post($author, $content, new HashTagArray(), $page, Status::READY, new PostId("prime2"));

        $repo->add($post);
        $found = $repo->findById(new PostId("prime"));
        $repo->add($post2);
        $found2 = $repo->findById(new PostId("prime2"));

        $this->assertInstanceOf(PostRepositoryInMemory::class, $repo);
        $this->assertInstanceOf(Post::class, $found);
        $this->assertEquals("prime", $found->getPostId());
        $this->assertFalse($found->getPostId()->equals($found2->getPostId()));
    }
}
