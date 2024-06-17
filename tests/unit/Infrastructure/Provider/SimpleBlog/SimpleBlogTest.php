<?php

namespace SocialNetworksPublisher\Tests\Infrastructure\Provider\SimpleBlog;

use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Application\Service\PublishPost\PostFactory;
use SocialNetworksPublisher\Application\Service\PublishPost\PublishPost;
use SocialNetworksPublisher\Application\Service\PublishPost\PublishPostRequest;
use SocialNetworksPublisher\Infrastructure\Persistence\Post\PostRepositoryInMemory;
use SocialNetworksPublisher\Infrastructure\Provider\FactorySocialNetworksApi;
use SocialNetworksPublisher\Infrastructure\Provider\SimpleBlog\SimpleBlog;

use function Safe\file_get_contents;
use function Safe\fopen;

class SimpleBlogTest extends TestCase
{
    private const TEXTCONTENT = "Ceci est un test multiple #Pedro \n\nCeci est un test multiple #Pedro \n\n";
    public function testOnePostOnSimpleBlog(): void
    {
        $blog = new SimpleBlog(getcwd() . "/var/simple_blog.txt");
        $requestHashTag = new PublishPostRequest(
            "facebook",
            "1a84fvb",
            "5adf78bfdsg",
            "Ceci est un test",
            "#Pedro",
        );

        $blog->postApiRequest((new PostFactory())->buildPostFromRequest($requestHashTag));
        $blogContent = file_get_contents(getcwd() . "/var/simple_blog.txt");

        $this->assertFileExists(getcwd() . "/var/simple_blog.txt");
        $this->assertEquals("Ceci est un test #Pedro \n\n", $blogContent);
    }

    public function testMultiplePostOnSimpleBlog(): void
    {
        $blog = new SimpleBlog(getcwd() . "/var/simple_blog_multiple.txt");
        $requestHashTag = new PublishPostRequest(
            "facebook",
            "1a84fvb",
            "5adf78bfdsg",
            "Ceci est un test multiple",
            "#Pedro",
        );

        $blog->postApiRequest((new PostFactory())->buildPostFromRequest($requestHashTag));
        $blog->postApiRequest((new PostFactory())->buildPostFromRequest($requestHashTag));
        $blogContent = file_get_contents(getcwd() . "/var/simple_blog_multiple.txt");

        $this->assertEquals(self::TEXTCONTENT, $blogContent);
    }

    public function testFileIsDeleted(): void
    {
        fopen(getcwd() . "/var/simple_blog.txt", 'c+b');
        (new SimpleBlogFake(getcwd() . "/var/simple_blog.txt"))->verifyFilePath(getcwd() . "/var/simple_blog.txt");
        $this->assertFileDoesNotExist(getcwd() . "/var/simple_blog.txt");
    }
}
