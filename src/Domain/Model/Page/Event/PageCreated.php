<?php

namespace SocialNetworksPublisher\Domain\Model\Page\Event;

use Phariscope\Event\Psr14\Event;

class PageCreated extends Event
{
    public function __construct(public readonly string $pageId)
    {
        parent::__construct();
    }
}
