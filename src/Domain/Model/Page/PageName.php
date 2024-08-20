<?php

namespace SocialNetworksPublisher\Domain\Model\Page;

class PageName
{
    public function __construct(private string $name)
    {
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
