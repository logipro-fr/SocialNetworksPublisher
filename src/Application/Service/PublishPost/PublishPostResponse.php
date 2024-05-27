<?php

namespace SocialNetworksPublisher\Application\Service\PublishPost;

class PublishPostResponse
{
    public function __construct(
        public readonly string $postId,
        public readonly string $socialNetworks,
    ) {
    }
}

