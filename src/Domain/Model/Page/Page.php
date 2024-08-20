<?php

namespace SocialNetworksPublisher\Domain\Model\Page;

use SocialNetworksPublisher\Domain\Model\Post\PostId;
use SocialNetworksPublisher\Domain\Model\Shared\SocialNetworks;

class Page
{
    /** @var array<PostId> */
    private array $postIds = [];

    public function __construct(
        private PageId $pageId,
        private PageName $name,
        private SocialNetworks $socialNetwork,
    ) {
    }

    public function getPageId(): PageId
    {
        return $this->pageId;
    }

    public function getName(): PageName
    {
        return $this->name;
    }

    public function getSocialNetwork(): SocialNetworks
    {
        return $this->socialNetwork;
    }
    /**
     * @return array<PostId>
     */
    public function getPostIds(): array
    {
        return $this->postIds;
    }

    public function addPost(PostId $postId): void
    {
        $this->postIds[] = $postId;
    }
}
