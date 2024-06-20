<?php

namespace SocialNetworksPublisher\Domain\Model\Post;

class PostId
{
    public function __construct(private string $id = "")
    {
        if (empty($this->id)) {
            $this->id = uniqid("pos_");
        }
    }
    public function equals(PostId $postId): bool
    {
        if ($this->id === $postId->id) {
            return true;
        }
        return false;
    }
    public function __toString(): string
    {
        return $this->id;
    }
}
