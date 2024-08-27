<?php

namespace SocialNetworksPublisher\Application\Service\Key\CreateKey;

class CreateKeyResponse
{
    public function __construct(
        readonly string $keyId
    ) {
    }
}
