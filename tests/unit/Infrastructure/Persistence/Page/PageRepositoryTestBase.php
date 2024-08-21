<?php

namespace SocialNetworksPublisher\Tests\Infrastructure\Persistence\Page;

use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Domain\Model\Page\Exceptions\PageAlreadyExistsException;
use SocialNetworksPublisher\Domain\Model\Page\Exceptions\PageNotFoundException;
use SocialNetworksPublisher\Domain\Model\Page\Page;
use SocialNetworksPublisher\Domain\Model\Page\PageId;
use SocialNetworksPublisher\Domain\Model\Page\PageName;
use SocialNetworksPublisher\Domain\Model\Page\PageRepositoryInterface;
use SocialNetworksPublisher\Domain\Model\Page\Post;
use SocialNetworksPublisher\Domain\Model\Page\PostStatus;
use SocialNetworksPublisher\Domain\Model\Shared\SocialNetworks;

abstract class PageRepositoryTestBase extends TestCase
{
    protected PageRepositoryInterface $pages;
    public function testAddAndFind(): void
    {
        $pageId = new PageId();
        $sut = new Page(
            $pageId,
            new PageName("page_name"),
            SocialNetworks::Twitter
        );

        $this->pages->add($sut);
        $foundPage = $this->pages->findById($pageId);
        $this->assertTrue($sut->getPageId()->equals($foundPage->getPageId()));
    }

    public function testNotFoundException(): void
    {
        $this->expectException(PageNotFoundException::class);
        $this->expectExceptionCode(PageNotFoundException::ERROR_CODE);
        $this->expectExceptionMessage(sprintf(PageNotFoundException::MESSAGE, "test"));

        $this->pages->findById(new PageId("test"));
    }

    public function testAddPostInRepository(): void
    {
        $pageId = new PageId();
        $page = new Page(
            $pageId,
            new PageName("page_name"),
            SocialNetworks::Twitter
        );

        $this->pages->add($page);
        $this->pages->addPost(
            $pageId,
            new Post(
                "content",
                PostStatus::READY
            )
        );
        $foundPage = $this->pages->findById($pageId);
        /** @var Post */
        $foundPost = $foundPage->getPosts()[0];
        $this->assertCount(1, $foundPage->getPosts());
        $this->assertEquals("content", $foundPost->getContent());
        $this->assertEquals(PostStatus::READY, $foundPost->getStatus());
    }

    // public function testAlreadyExistsException(): void {
    //     $pageId = new PageId();
    //     $page1 = new Page(
    //         $pageId,
    //         new PageName("page_name"),
    //         SocialNetworks::Twitter
    //     );
    //     $page2 = new Page(
    //         $pageId,
    //         new PageName("page_name"),
    //         SocialNetworks::Twitter
    //     );

    //     $this->expectException(PageAlreadyExistsException::class);
    //     $this->expectExceptionCode(PageAlreadyExistsException::ERROR_CODE);
    //     $this->expectExceptionMessage(sprintf(PageAlreadyExistsException::MESSAGE, $pageId));

    //     $this->pages->add($page1);
    //     $this->pages->add($page2);
    // }
}
