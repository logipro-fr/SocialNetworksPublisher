<?php

namespace SocialNetworksPublisher\Tests\Application\Service\Page\CreatePage;

use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Application\Service\Page\CreatePage\CreatePage;
use SocialNetworksPublisher\Application\Service\Page\CreatePage\CreatePageRequest;
use SocialNetworksPublisher\Domain\Model\Page\PageId;
use SocialNetworksPublisher\Domain\Model\Shared\SocialNetworks;
use SocialNetworksPublisher\Infrastructure\Persistence\Page\PageRepositoryInMemory;

class PageCreateTest extends TestCase {
    public function testCreatePage(): void {
        $pages = new PageRepositoryInMemory();
        $service = new CreatePage($pages);
        $service->execute(new CreatePageRequest(
            "test_page_name",
            "Twitter"
        ));
        $response = $service->getResponse();

        $page = $pages->findById(new PageId($response->pageId));
        $this->assertEquals("test_page_name", $page->getName());
        $this->assertEquals(SocialNetworks::Twitter, $page->getSocialNetwork());
    }
}