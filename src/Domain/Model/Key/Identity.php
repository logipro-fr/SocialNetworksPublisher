<?php

namespace SocialNetworksPublisher\Domain\Model\Key;

use SocialNetworksPublisher\Domain\Model\Page\PageId;

class Identity
{
    private PageId $value;

    public function __construct(PageId $value)
    {
        $this->value = $value;
    }

    public function getValue(): PageId
    {
        return $this->value;
    }

    public function __toString()
    {
        return $this->value;
    }
}
