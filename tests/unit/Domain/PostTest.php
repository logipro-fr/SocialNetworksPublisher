<?php

namespace SocialNetworks\Tests\Domain;

use PHPUnit\Framework\TestCase;
use SocialNetworks\Domain\Author;
use SocialNetworks\Domain\Content;
use SocialNetworks\Domain\Post;
use SocialNetworks\Domain\Exceptions\EmptyPostContentException;
use SocialNetworks\Domain\HashTag;
use SocialNetworks\Domain\Page;
use SocialNetworks\Domain\PostId;
use SocialNetworks\Domain\Status;
use SocialNetworks\Domain\TargetStatus;

class PostTest extends TestCase
{
    public function testValidPost(): void
    {
        $author = new Author(Author::ORGANIZATION, '123456', 'Logipro');
        $content = new Content("Lorem ipsum dolor sit amet, consectetur adipiscing elit.");
        $page = new Page("Accident Prediction", "123456");
        $targetStatus = new TargetStatus(Status::PUBLISHED);

        $post = new Post($author, $content, new HashTag(), $page, $targetStatus);

        $this->assertInstanceOf(Post::class, $post);
        $this->assertEquals($author, $post->getAuthor());
        $this->assertEquals($content, $post->getContent());
        $this->assertEquals($page, $post->getPage());
        $this->assertEquals(new HashTag(), $post->getHashTags());
        $this->assertEquals($targetStatus, $post->getTargetStatus());
    }

    public function testValidPostWithHashTag(): void
    {
        $author = new Author(Author::ORGANIZATION, '123456', 'Logipro');
        $content = new Content("Lorem ipsum dolor sit amet, consectetur adipiscing elit.");
        $page = new Page("Accident Prediction", "123456");
        $hashtag = new HashTag("#test1, #test2");
        $targetStatus = new TargetStatus(Status::PUBLISHED);

        $post = new Post($author, $content, $hashtag, $page, $targetStatus);

        $this->assertInstanceOf(Post::class, $post);
        $this->assertEquals($author, $post->getAuthor());
        $this->assertEquals($content, $post->getContent());
        $this->assertEquals($hashtag, $post->getHashTags());
        $this->assertEquals($page, $post->getPage());
        $this->assertEquals($targetStatus, $post->getTargetStatus());
    }

    public function testPostId(): void
    {
        $author = new Author(Author::ORGANIZATION, '123456', 'Logipro');
        $content = new Content("Lorem ipsum dolor sit amet, consectetur adipiscing elit.");
        $page = new Page("Accident Prediction", "123456");
        $targetStatus = new TargetStatus(Status::PUBLISHED);

        $post = new Post($author, $content, new HashTag(), $page, $targetStatus);

        $this->assertStringStartsWith('pst_', $post->getId());
    }

    public function testInjectedPostId(): void
    {
        $author = new Author(Author::ORGANIZATION, '123456', 'Logipro');
        $content = new Content("Lorem ipsum dolor sit amet, consectetur adipiscing elit.");
        $page = new Page("Accident Prediction", "123456");
        $targetStatus = new TargetStatus(Status::PUBLISHED);

        $post = new Post($author, $content, new HashTag("#cool"), $page, $targetStatus, new PostId("pst_test"));

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
