<?php

namespace SocialNetworksPublisher\Domain\Model\Post\Event;

use Phariscope\Event\Psr14\Event;

class PostCreated extends Event
{
    public function __construct(public readonly string $postId)
    {
    }
}
