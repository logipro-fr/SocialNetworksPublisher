<?php

namespace SocialNetworksPublisher\Domain\Model\Post;

use DateTimeImmutable;
use SocialNetworksPublisher\Domain\EventFacade\EventFacade;
use SocialNetworksPublisher\Domain\Model\Post\Event\PostCreated;

class Post
{
    public function __construct(
        private Author $author,
        private Content $content,
        private HashTagArray $hashTags,
        private Page $page,
        private Status $status,
        private PostId $postId = new PostId(),
        private DateTimeImmutable $createdAt = new DateTimeImmutable()
    ) {
        (new EventFacade())->dispatch(new PostCreated($this->postId));
    }

    public function getAuthor(): Author
    {
        return $this->author;
    }

    public function getContent(): Content
    {
        return $this->content;
    }

    public function getPage(): Page
    {
        return $this->page;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function getPostId(): PostId
    {
        return $this->postId;
    }

    public function getHashTags(): HashTagArray
    {
        return $this->hashTags;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
    public function setStatus(Status $newStatus): void {
        $this->status = $newStatus;
    }
}
