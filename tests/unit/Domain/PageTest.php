<?php

namespace SocialNetworks\Tests\Domain;

use PHPUnit\Framework\TestCase;
use SocialNetworks\Domain\Author;
use SocialNetworks\Domain\Content;
use SocialNetworks\Domain\Exceptions\EmptyPageIdException;
use SocialNetworks\Domain\Exceptions\EmptyPageNameException;
use SocialNetworks\Domain\HashTag;
use SocialNetworks\Domain\Page;
use SocialNetworks\Domain\Post;
use SocialNetworks\Domain\PostId;
use SocialNetworks\Domain\Status;
use SocialNetworks\Domain\TargetStatus;

class PageTest extends TestCase
{
    private Post $post;
    private Post $post2;
    public function setUp(): void
    {
        $author = new Author(Author::ORGANIZATION, '123456', 'Logipro');
        $content = new Content("Lorem ipsum dolor sit amet, consectetur adipiscing elit.");
        $hashtag = new HashTag("#test");
        $page = new Page("Accident Prediction", "123456");
        $targetStatus = new TargetStatus(Status::PUBLISHED);

        $this->post = new Post($author, $content, $hashtag, $page, $targetStatus, new PostId("Post 1"));
        $this->post2 = new Post($author, $content, $hashtag, $page, $targetStatus, new PostId("Post 2"));
    }
    public function testValidPage(): void
    {
        $page = new Page("Accident Prediction", "123456");

        $this->assertEquals("Accident Prediction", $page->getName());
        $this->assertEquals("123456", $page->getId());
    }

    public function testAddPostToPage(): void
    {
        $page = new Page("Accident Prediction", "123456");
        $page->addPost($this->post->getId());
        $postsArray = $page->getPostsId();
        $this->assertEquals("Post 1", $postsArray[0]);
        $page->addPost($this->post2->getId());
        $postsArray = $page->getPostsId();
        $this->assertEquals("Post 2", $postsArray[1]);
    }

    public function testBadNameException(): void
    {
        $this->expectException(EmptyPageNameException::class);

        new Page("", "123456");
    }

    public function testBadIdException(): void
    {
        $this->expectException(EmptyPageIdException::class);

        new Page("Accident Prediction", "");
    }
}
