<?php

namespace SocialNetworksPublisher\Domain\Model\Page;

class PostId
{
    public const PREFIX = "pos_";
    public function __construct(private string $id = "")
    {
        if (empty($this->id)) {
            $this->id = uniqid(self::PREFIX);
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
