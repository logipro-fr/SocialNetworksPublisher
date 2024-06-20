<?php

namespace SocialNetworksPublisher\Application\Service\PublishPost;

class ProviderResponse
{
    public function __construct(
        public readonly bool $success,
    ) {
    }
}
