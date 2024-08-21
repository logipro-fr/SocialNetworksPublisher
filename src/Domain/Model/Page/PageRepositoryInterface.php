<?php

namespace SocialNetworksPublisher\Domain\Model\Page;

interface PageRepositoryInterface
{
    public function add(Page $page): void;

    public function findById(PageId $pageId): Page;

    public function addPost(PageId $pageId, Post $post): void;
}
