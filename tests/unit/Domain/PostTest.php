<?php

namespace SocialNetworksPublisher\Tests\Domain;

use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Domain\Author;
use SocialNetworksPublisher\Domain\Content;
use SocialNetworksPublisher\Domain\Post;
use SocialNetworksPublisher\Domain\HashTag;
use SocialNetworksPublisher\Domain\Page;
use SocialNetworksPublisher\Domain\PostId;
use SocialNetworksPublisher\Domain\Status;

class PostTest extends TestCase
{
    public function testValidPost(): void
    {
        $author = new Author('facebook', '123za45g');
        $content = new Content("Lorem ipsum dolor sit amet, consectetur adipiscing elit.");
        $page = new Page("facebook","98ad48644");

        $post = new Post($author, $content, new HashTag(), $page, Status::READY);

        $this->assertInstanceOf(Post::class, $post);
        $this->assertEquals($author, $post->getAuthor());
        $this->assertEquals($content, $post->getContent());
        $this->assertEquals($page, $post->getPage());
        $this->assertEquals(new HashTag(), $post->getHashTags());
        $this->assertEquals(Status::READY, $post->getStatus());
    }

    public function testValidPostWithHashTag(): void
    {
        $author = new Author('facebook', '123za45g');
        $content = new Content("Lorem ipsum dolor sit amet, consectetur adipiscing elit.");
        $page = new Page("facebook","98ad48644");
        $hashtag = new HashTag("#test1, #test2");
        $post = new Post($author, $content, $hashtag, $page, Status::DRAFT);

        $this->assertInstanceOf(Post::class, $post);
        $this->assertEquals($author, $post->getAuthor());
        $this->assertEquals($content, $post->getContent());
        $this->assertEquals($hashtag, $post->getHashTags());
        $this->assertEquals($page, $post->getPage());
        $this->assertEquals(Status::DRAFT, $post->getStatus());
    }

    public function testPostId(): void
    {
        $author = new Author('facebook', '123za45g');
        $content = new Content("Lorem ipsum dolor sit amet, consectetur adipiscing elit.");
        $page = new Page("facebook","98ad48644");
        $post = new Post($author, $content, new HashTag(), $page, Status::READY);
        $this->assertStringStartsWith('pst_', $post->getId());
    }

    public function testInjectedPostId(): void
    {
        $author = new Author('facebook', '123za45g');
        $content = new Content("Lorem ipsum dolor sit amet, consectetur adipiscing elit.");
        $page = new Page("facebook","98ad48644");
        $post = new Post($author, $content, new HashTag("#cool"), $page, Status::READY, new PostId("pst_test"));
        $this->assertEquals('pst_test', $post->getId());
    }



    // public function testEmptyPostContentException(): void
    // {
    //     $this->expectException(EmptyPostContentException::class);

    //     $author = new Author(Author::ORGANIZATION, '123456', 'Logipro');
    //     $content = new Content("");
    //     $page = new Page("Accident Prediction", "123456");
    //     $targetStatus = new TargetStatus("PUBLISHED");

    //     new Post($author, $content, $page, $targetStatus);
    // }
}
