<?php

namespace SocialNetworksPublisher\Domain\Model\Post;

class PageId
{
    public function __construct(private string $id = "")
    {
        if (empty($this->id)) {
            $this->id = uniqid("pag_");
        }
    }

    public function equals(PageId $pageId): bool
    {
        if ($this->id === $pageId->id) {
            return true;
        }
        return false;
    }
    public function __toString(): string
    {
        return $this->id;
    }
}
