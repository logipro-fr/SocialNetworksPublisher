<?php

namespace SocialNetworksPublisher\Tests\Application\Service\PublishPost;

use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Application\Service\PublishPost\PublishPost;
use SocialNetworksPublisher\Domain\Model\Page\Page;
use SocialNetworksPublisher\Domain\Model\Page\PageId;
use SocialNetworksPublisher\Domain\Model\Page\PageName;
use SocialNetworksPublisher\Domain\Model\Page\Post;
use SocialNetworksPublisher\Domain\Model\Page\PostId;
use SocialNetworksPublisher\Domain\Model\Page\PostStatus;
use SocialNetworksPublisher\Domain\Model\Shared\SocialNetworks;
use SocialNetworksPublisher\Infrastructure\Persistence\Page\PageRepositoryInMemory;
use SocialNetworksPublisher\Infrastructure\Provider\FactorySocialNetworksApi;
use Symfony\Component\HttpClient\MockHttpClient;

class PublishPostTest extends TestCase
{
    private PageRepositoryInMemory $pages;

    protected function setUp(): void
    {
        $page1 = new Page(
            new PageId("page_1"),
            new PageName("page_1"),
            SocialNetworks::Twitter
        );

        $page2 = new Page(
            new PageId("page_2"),
            new PageName("page_2"),
            SocialNetworks::Twitter
        );

        $post1 = new Post(
            "post1",
            PostStatus::READY,
            new PostId("post_1")
        );

        $post2 = new Post(
            "post2",
            PostStatus::READY,
            new PostId("post_2")
        );
        $post3 = new Post(
            "post3",
            PostStatus::READY,
            new PostId("post_3")
        );

        $page1->addPost($post1);
        $page2->addPost($post2);
        $page2->addPost($post3);

        $this->pages = new PageRepositoryInMemory();
        $this->pages->add($page1);
        $this->pages->add($page2);
    }

    public function testPublishPost(): void
    {
        $service = new PublishPost(
            $this->pages,
            new FactorySocialNetworksApi(
                new MockHttpClient()
            )
        );

        $service->execute();

        $response = $service->getResponse();
        $page1 = $this->pages->findById(new PageId("page_1"));
        $page2 = $this->pages->findById(new PageId("page_2"));

        $this->assertEquals(["post_1","post_2","post_3"], $response->postIds);
        $this->assertEquals(PostStatus::PUBLISHED, $page1->getPosts()[0]->getStatus());
        $this->assertEquals(PostStatus::PUBLISHED, $page2->getPosts()[0]->getStatus());
        $this->assertEquals(PostStatus::PUBLISHED, $page2->getPosts()[1]->getStatus());
    }
}
