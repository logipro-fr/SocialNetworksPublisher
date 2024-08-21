<?php

namespace SocialNetworksPublisher\Tests\Application\Service\Page\AddPost;

use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Application\Service\Page\AddPost\AddPost;
use SocialNetworksPublisher\Application\Service\Page\AddPost\AddPostRequest;
use SocialNetworksPublisher\Application\Service\Page\CreatePage\CreatePage;
use SocialNetworksPublisher\Application\Service\Page\CreatePage\CreatePageRequest;
use SocialNetworksPublisher\Domain\Model\Page\Page;
use SocialNetworksPublisher\Domain\Model\Page\PageId;
use SocialNetworksPublisher\Domain\Model\Page\PageRepositoryInterface;
use SocialNetworksPublisher\Infrastructure\Persistence\Page\PageRepositoryInMemory;

class AddPostTest extends TestCase
{
    private Page $page;
    private PageRepositoryInterface $pages;
    public function setUp(): void
    {
        $this->pages = new PageRepositoryInMemory();
        $service = new CreatePage($this->pages);
        $service->execute(new CreatePageRequest(
            "test_page_name",
            "Twitter"
        ));
        $response = $service->getResponse();

        $this->page = $this->pages->findById(new PageId($response->pageId));
    }

    public function testAddPage(): void
    {
        $request = new AddPostRequest(
            $this->page->getPageId(),
            "simple_test_content",
        );
        $service = new AddPost(
            $this->pages
        );

        $service->execute($request);

        $response = $service->getResponse();

        $page = $this->pages->findById(new PageId($this->page->getPageId()));

        $this->assertCount(1, $page->getPosts());
    }
}
