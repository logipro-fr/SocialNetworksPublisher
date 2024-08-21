<?php

namespace SocialNetworksPublisher\Infrastructure\Persistence\Page;

use SocialNetworksPublisher\Domain\Model\Page\Exceptions\PageAlreadyExistsException;
use SocialNetworksPublisher\Domain\Model\Page\Exceptions\PageNotFoundException;
use SocialNetworksPublisher\Domain\Model\Page\Page;
use SocialNetworksPublisher\Domain\Model\Page\PageId;
use SocialNetworksPublisher\Domain\Model\Page\PageRepositoryInterface;
use SocialNetworksPublisher\Domain\Model\Page\Post;

class PageRepositoryInMemory implements PageRepositoryInterface
{
    /** @var array<string, Page> */
    private array $pages = [];
    public function add(Page $page): void
    {
        if (array_key_exists($id = $page->getPageId()->__toString(), $this->pages)) {
            throw new PageAlreadyExistsException($id);
        }
        $this->pages[$page->getPageId()->__toString()] = $page;
    }

    public function findById(PageId $pageId): Page
    {
        if (!array_key_exists($id = $pageId->__toString(), $this->pages)) {
            throw new PageNotFoundException($id);
        }

        return $this->pages[$id];
    }

    public function addPost(PageId $pageId, Post $post): void
    {
        $page = $this->findById($pageId);
        $page->addPost($post);
    }
}
