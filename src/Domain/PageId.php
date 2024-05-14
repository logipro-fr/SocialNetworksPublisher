<?php

namespace SocialNetworksPublisher\Domain;

class PageId
{
    public function __construct(private string $id = "")
    {
        if (empty($this->id)) {
            $this->id = uniqid("pge_");
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
