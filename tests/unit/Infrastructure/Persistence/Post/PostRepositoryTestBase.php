<?php

namespace SocialNetworksPublisher\Tests\Infrastructure\Persistence\Post;

use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Domain\Model\Post\Author;
use SocialNetworksPublisher\Domain\Model\Post\Content;
use SocialNetworksPublisher\Domain\Model\Post\Exceptions\PostNotFoundException;
use SocialNetworksPublisher\Domain\Model\Post\HashTagArray;
use SocialNetworksPublisher\Domain\Model\Post\HashTagArrayFactory;
use SocialNetworksPublisher\Domain\Model\Post\Page;
use SocialNetworksPublisher\Domain\Model\Post\Post;
use SocialNetworksPublisher\Domain\Model\Post\PostId;
use SocialNetworksPublisher\Domain\Model\Post\PostRepositoryInterface;
use SocialNetworksPublisher\Domain\Model\Post\Status;
use SocialNetworksPublisher\Infrastructure\Persistence\Post\PostRepositoryInMemory;

abstract class PostRepositoryTestBase extends TestCase
{
    protected PostRepositoryInterface $postRepository;

    public function testFindById(): void
    {
        $author = new Author('facebook', '123za45g');
        $content = new Content("Lorem ipsum dolor sit amet, consectetur adipiscing elit.");
        $page = new Page("facebook", "98ad48644");
        $post = new Post(
            $author,
            $content,
            (new HashTagArrayFactory())->buildHashTagArrayFromSentence("1,2,3", ','),
            $page,
            Status::READY,
            new PostId("prime")
        );
        $post2 = new Post($author, $content, new HashTagArray(), $page, Status::READY, new PostId("prime2"));

        $this->postRepository->add($post);
        $found = $this->postRepository->findById(new PostId("prime"));
        $this->postRepository->add($post2);
        $found2 = $this->postRepository->findById(new PostId("prime2"));

        $this->assertInstanceOf(PostRepositoryInterface::class, $this->postRepository);
        $this->assertInstanceOf(Post::class, $found);
        $this->assertEquals("prime", $found->getPostId());
        $this->assertFalse($found->getPostId()->equals($found2->getPostId()));
    }

    public function testFindByIdException(): void
    {
        $this->expectException(PostNotFoundException::class);
        $this->expectExceptionMessage("Error can't find the postId");
        $this->expectExceptionCode(400);
        $this->postRepository->findById(new PostId("prime54845"));
    }
}
