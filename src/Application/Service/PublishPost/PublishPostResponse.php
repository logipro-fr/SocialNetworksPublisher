<?php

namespace SocialNetworksPublisher\Application\Service\PublishPost;

class PublishPostResponse
{
    public function __construct(
        /** @var array<string> */
        public readonly array $postIds,
    ) {
    }
}
