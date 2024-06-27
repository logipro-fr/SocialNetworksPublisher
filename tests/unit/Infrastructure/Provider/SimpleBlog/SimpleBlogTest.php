<?php

namespace SocialNetworksPublisher\Tests\Infrastructure\Provider\SimpleBlog;

use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Application\Service\PublishPost\PostFactory;
use SocialNetworksPublisher\Application\Service\PublishPost\PublishPost;
use SocialNetworksPublisher\Application\Service\PublishPost\PublishPostRequest;
use SocialNetworksPublisher\Infrastructure\Persistence\Post\PostRepositoryInMemory;
use SocialNetworksPublisher\Infrastructure\Provider\FactorySocialNetworksApi;
use SocialNetworksPublisher\Infrastructure\Provider\SimpleBlog\Path;
use SocialNetworksPublisher\Infrastructure\Provider\SimpleBlog\SimpleBlog;

use function Safe\file_get_contents;
use function Safe\fopen;

class SimpleBlogTest extends TestCase
{
    private const TEXTCONTENT = "Ceci est un test multiple #Pedro \n\nCeci est un test multiple #Pedro \n\n";
    protected function setUp(): void
    {
    }
    public function testOnePostOnSimpleBlog(): void
    {
        $blog = new SimpleBlog("test_simple_blog.txt");
        $requestHashTag = new PublishPostRequest(
            "SimpleBlog",
            "1a84fvb",
            "5adf78bfdsg",
            "Ceci est un test",
            "#Pedro",
        );

        $response = $blog->postApiRequest((new PostFactory())->buildPostFromRequest($requestHashTag));
        $blogContent = file_get_contents(getcwd() . "/var/test_simple_blog.txt");

        $this->assertFileExists(getcwd() . "/var/test_simple_blog.txt");
        $this->assertEquals("Ceci est un test #Pedro \n\n", $blogContent);
        $this->assertTrue($response->success);
    }

    public function testMultiplePostOnSimpleBlog(): void
    {
        $blog = new SimpleBlog("test_simple_blog_multiple.txt");
        $requestHashTag = new PublishPostRequest(
            "SimpleBlog",
            "1a84fvb",
            "5adf78bfdsg",
            "Ceci est un test multiple",
            "#Pedro",
        );

        $response = $blog->postApiRequest((new PostFactory())->buildPostFromRequest($requestHashTag));
        $response2 = $blog->postApiRequest((new PostFactory())->buildPostFromRequest($requestHashTag));
        $blogContent = file_get_contents(getcwd() . "/var/test_simple_blog_multiple.txt");

        $this->assertTrue($response->success);
        $this->assertTrue($response2->success);
        $this->assertEquals(self::TEXTCONTENT, $blogContent);
    }

    public function testFileIsDeleted(): void
    {
        fopen(getcwd() . "/var/test_simple_blog.txt", 'c+b');
        (new SimpleBlogFake("test_simple_blog.txt"))->verifyFilePath(getcwd() . "/var/test_simple_blog.txt");
        $this->assertFileDoesNotExist(getcwd() . "/var/test_simple_blog.txt");
    }
}
