<?php

namespace SocialNetworksPublisher\Infrastructure\Provider;

class ProviderResponse
{
    public function __construct(
        public readonly string $postId,
        public readonly string $socialNetworks,
    ) {
    }
}
