<?php

namespace SocialNetworksPublisher\Application\Service\PublishPost;

class PublishPostResponse
{
    public function __construct(
        public readonly int $statusCode,
        public readonly string $status
    ) {
    }
}
