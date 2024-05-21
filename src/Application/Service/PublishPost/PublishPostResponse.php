<?php

namespace SocialNetworksPublisher\Application\Service\PublishPost;

use SocialNetworksPublisher\Domain\Model\Post\PostId;

class PublishPostResponse
{
    public function __construct(
        public readonly bool $success,
        public readonly int $statusCode,
        public readonly object $data,
        public readonly string $message = "",
    ) {
    }
}
