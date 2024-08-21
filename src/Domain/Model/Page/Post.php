<?php

namespace SocialNetworksPublisher\Domain\Model\Page;

use DateTimeImmutable;
use SocialNetworksPublisher\Domain\EventFacade\EventFacade;
use SocialNetworksPublisher\Domain\Model\Post\Event\PostCreated;

class Post
{
    public function __construct(
        private string $content,
        private PostStatus $status,
        private PostId $postId = new PostId(),
        private DateTimeImmutable $createdAt = new DateTimeImmutable()
    ) {
        (new EventFacade())->dispatch(new PostCreated($this->postId));
    }

    public function getContent(): string
    {
        return $this->content;
    }


    public function getStatus(): PostStatus
    {
        return $this->status;
    }

    public function getPostId(): PostId
    {
        return $this->postId;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
    public function setStatus(PostStatus $newStatus): void
    {
        $this->status = $newStatus;
    }
}
