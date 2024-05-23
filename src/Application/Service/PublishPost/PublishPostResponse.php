<?php

namespace SocialNetworksPublisher\Application\Service\PublishPost;

use SocialNetworksPublisher\Domain\Model\Post\PostId;

class PublishPostResponse
{
    public function __construct(
        public readonly string $postId,
        public readonly string $socialNetworks,
    ) {
    }
}
