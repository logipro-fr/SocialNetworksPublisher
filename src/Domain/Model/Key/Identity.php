<?php

namespace SocialNetworksPublisher\Domain\Model\Key;

use SocialNetworksPublisher\Domain\Model\Page\PageId;

class Identity
{
    public function __construct(private string $value)
    {
        $this->value = $value;
    }

    public function getPageIdValue(): PageId
    {
        return new PageId($this->value);
    }

    public function __toString()
    {
        return $this->value;
    }
}
