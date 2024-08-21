<?php

namespace SocialNetworksPublisher\Domain\Model\Page;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Safe\DateTimeImmutable;
use SocialNetworksPublisher\Domain\EventFacade\EventFacade;
use SocialNetworksPublisher\Domain\Model\Page\Event\PageCreated;
use SocialNetworksPublisher\Domain\Model\Page\Post;
use SocialNetworksPublisher\Domain\Model\Shared\SocialNetworks;

class Page
{
    /** @var Collection<int, Post> */
    private Collection $posts;

    private DateTimeImmutable $createdAt;
    public function __construct(
        private PageId $pageId,
        private PageName $name,
        private SocialNetworks $socialNetwork,
    ) {
        $this->createdAt = new DateTimeImmutable();
        $this->posts = new ArrayCollection();

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
     * @return ArrayCollection<Post>
     */
    public function getPosts(): ArrayCollection
    {
        return $this->posts;
    }

    public function getCreatedAt(): DateTimeImmutable {
        return $this->createdAt;
    }

    public function addPost(Post $post): void
    {
        $this->posts->add($post);
    }
}
