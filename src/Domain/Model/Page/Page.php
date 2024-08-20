<?php

namespace SocialNetworksPublisher\Domain\Model\Page;

use Safe\DateTimeImmutable;
use SocialNetworksPublisher\Domain\EventFacade\EventFacade;
use SocialNetworksPublisher\Domain\Model\Page\Event\PageCreated;
use SocialNetworksPublisher\Domain\Model\Post\PostId;
use SocialNetworksPublisher\Domain\Model\Shared\SocialNetworks;

class Page
{
    /** @var array<PostId> */
    private array $postIds = [];

    private DateTimeImmutable $createdAt;
    public function __construct(
        private PageId $pageId,
        private PageName $name,
        private SocialNetworks $socialNetwork,
    ) {
        $this->createdAt = new DateTimeImmutable();

        (new EventFacade())->dispatch(new PageCreated($this->pageId));

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

    public function getCreatedAt(): DateTimeImmutable {
        return $this->createdAt;
    }

    public function addPost(PostId $postId): void
    {
        $this->postIds[] = $postId;
    }
}
