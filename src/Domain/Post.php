<?php

namespace SocialNetworksPublisher\Domain;

class Post
{
    private PostId $id;
    public function __construct(
        private Author $author,
        private Content $content,
        private HashTag $hashTags,
        private Page $page,
        private Status $status,
        ?PostId $postId = null,
    ) {
        if ($postId == null) {
            $this->id = new PostId();
        } else {
            $this->id = $postId;
        }
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

    public function getId(): PostId
    {
        return $this->id;
    }

    public function getHashTags(): HashTag
    {
        return $this->hashTags;
    }
}
