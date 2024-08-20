<?php

namespace SocialNetworksPublisher\Domain\Model\Page;

class PageId
{
    public const PREFIX = "pag_";
    public function __construct(private string $id = "")
    {
        if (empty($this->id)) {
            $this->id = uniqid(self::PREFIX);
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

    public function getId(): string
    {
        return $this->id;
    }
}
