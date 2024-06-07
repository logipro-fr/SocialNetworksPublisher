<?php

namespace SocialNetworksPublisher\Tests\Domain\Model\Post;

use DateTimeImmutable;
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
use SocialNetworksPublisher\Domain\Model\Post\HashTagArrayFactory;
use SocialNetworksPublisher\Domain\Model\Post\PostId;
use SocialNetworksPublisher\Domain\Model\Post\Status;

class PostTest extends TestCase
{
    private Post $post;
    private Author $author;
    private Content $content;
    private Page $page;
    protected function setUp(): void
    {
        $this->author = new Author('facebook', '123za45g');
        $this->content = new Content("Lorem ipsum dolor sit amet, consectetur adipiscing elit.");
        $this->page = new Page("facebook", "98ad48644");
        $this->post = new Post($this->author, $this->content, new HashTagArray(), $this->page, Status::READY);
    }
    public function testValidPost(): void
    {
        $this->assertInstanceOf(Post::class, $this->post);
        $this->assertEquals($this->author, $this->post->getAuthor());
        $this->assertEquals($this->content, $this->post->getContent());
        $this->assertEquals($this->page, $this->post->getPage());
        $this->assertEquals(new HashTagArray(), $this->post->getHashTags());
        $this->assertEquals(Status::READY, $this->post->getStatus());
    }

    public function testValidPostWithHashTag(): void
    {
        $hashTags = (new HashTagArrayFactory)->buildHashTagArrayFromSentence("#test1, #test2", ", ");
        $this->post = new Post($this->author, $this->content, $hashTags, $this->page, Status::READY);

        $this->assertInstanceOf(Post::class, $this->post);
        $this->assertEquals($this->author, $this->post->getAuthor());
        $this->assertEquals($this->content, $this->post->getContent());
        $this->assertEquals($this->page, $this->post->getPage());
        $this->assertEquals($hashTags, $this->post->getHashTags());
        $this->assertEquals(Status::READY, $this->post->getStatus());
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
        $this->post = new Post(
            $this->author,
            $this->content,
            new HashTagArray(new HashTag("#cool")),
            $this->page,
            Status::READY,
            new PostId("pos_test")
        );

        $this->assertEquals('pos_test', $this->post->getPostId());
    }

    public function testPostCreatedSentWhenPostHasBeenCreated(): void
    {
        $spy = new SpyListener();
        (new EventFacade())->subscribe($spy);
        //EventPublisherBase::instance()->distributeImmmediatly();
        $this->post = new Post($this->author, $this->content, new HashTagArray(), $this->page, Status::READY);

        (new EventFacade())->distribute();
        /** @var PostCreated */
        $event = $spy->domainEvent;

        $this->assertInstanceOf(PostCreated::class, $spy->domainEvent);
        $this->assertEquals($this->post->getPostId()->__toString(), $event->postId);
        $this->assertInstanceOf(DateTimeImmutable::class, $event->occurredOn());
    }

    public function testPostCreatedAt(): void
    {
        $date = new DateTimeImmutable();
        $post = new Post($this->author, $this->content, new HashTagArray(), $this->page, Status::READY, new PostId(), $date);
        $this->assertEquals($date, $post->getCreatedAt());
    }

    public function testSetStatus(): void {
        $this->post->setStatus(Status::PUBLISHED);
        $this->assertEquals(Status::PUBLISHED, $this->post->getStatus());
    }
}
