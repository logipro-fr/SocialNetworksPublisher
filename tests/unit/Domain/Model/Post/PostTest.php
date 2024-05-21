<?php

namespace SocialNetworksPublisher\Tests\Domain\Model\Post;

use Phariscope\Event\EventPublisherBase;
use Phariscope\Event\Tools\SpyListener;
use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Domain\EventFacade\EventFacade;
use SocialNetworksPublisher\Domain\Model\Post\Author;
use SocialNetworksPublisher\Domain\Model\Post\Content;
use SocialNetworksPublisher\Domain\Model\Post\Post;
use SocialNetworksPublisher\Domain\Model\Post\HashTag;
use SocialNetworksPublisher\Domain\Model\Post\Page;
use SocialNetworksPublisher\Domain\Model\Post\Event\PostCreated;
use SocialNetworksPublisher\Domain\Model\Post\HashTagArray;
use SocialNetworksPublisher\Domain\Model\Post\PostId;
use SocialNetworksPublisher\Domain\Model\Post\Status;

class PostTest extends TestCase
{
    public function testValidPost(): void
    {
        $author = new Author('facebook', '123za45g');
        $content = new Content("Lorem ipsum dolor sit amet, consectetur adipiscing elit.");
        $page = new Page("facebook", "98ad48644");

        $post = new Post($author, $content, new HashTagArray(), $page, Status::READY);

        $this->assertInstanceOf(Post::class, $post);
        $this->assertEquals($author, $post->getAuthor());
        $this->assertEquals($content, $post->getContent());
        $this->assertEquals($page, $post->getPage());
        $this->assertEquals(new HashTagArray(), $post->getHashTags());
        $this->assertEquals(Status::READY, $post->getStatus());
    }

    public function testValidPostWithHashTag(): void
    {
        $author = new Author('facebook', '123za45g');
        $content = new Content("Lorem ipsum dolor sit amet, consectetur adipiscing elit.");
        $page = new Page("facebook", "98ad48644");
        $hashTag1 = new HashTag("#test1");
        $hashTag2 = new HashTag("#test2");
        $hashTags = new HashTagArray($hashTag1);
        $hashTags->add($hashTag2);

        $post = new Post($author, $content, $hashTags, $page, Status::DRAFT);

        $this->assertInstanceOf(Post::class, $post);
        $this->assertEquals($author, $post->getAuthor());
        $this->assertEquals($content, $post->getContent());
        $this->assertEquals($hashTags, $post->getHashTags());
        $this->assertEquals($page, $post->getPage());
        $this->assertEquals(Status::DRAFT, $post->getStatus());
    }

    public function testPostId(): void
    {
        $author = new Author('facebook', '123za45g');
        $content = new Content("Lorem ipsum dolor sit amet, consectetur adipiscing elit.");
        $page = new Page("facebook", "98ad48644");

        $post = new Post($author, $content, new HashTagArray(), $page, Status::READY);

        $this->assertStringStartsWith('pos_', $post->getPostId());
    }

    public function testInjectedPostId(): void
    {
        $author = new Author('facebook', '123za45g');
        $content = new Content("Lorem ipsum dolor sit amet, consectetur adipiscing elit.");
        $page = new Page("facebook", "98ad48644");

        $post = new Post(
            $author,
            $content,
            new HashTagArray(new HashTag("#cool")),
            $page,
            Status::READY,
            new PostId("pos_test")
        );

        $this->assertEquals('pos_test', $post->getPostId());
    }

    public function testPostCreatedSentWhenPostHasBeenCreated(): void
    {
        $spy = new SpyListener();
        (new EventFacade())->subscribe($spy);
        //EventPublisherBase::instance()->distributeImmmediatly();
        $author = new Author('facebook', '123za45g');
        $content = new Content("Lorem ipsum dolor sit amet, consectetur adipiscing elit.");
        $page = new Page("facebook", "98ad48644");
        $post = new Post($author, $content, new HashTagArray(), $page, Status::READY);

        (new EventFacade())->distribute();
        /** @var PostCreated */
        $event = $spy->domainEvent;

        $this->assertInstanceOf(PostCreated::class, $spy->domainEvent);
        $this->assertEquals($post->getPostId()->__toString(), $event->postId);
    }
}
