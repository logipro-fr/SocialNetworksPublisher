<?php

namespace SocialNetworksPublisher\Application\Service\Key\CreateKey;

abstract class AbstractCreateKeyRequest
{
    public function __construct(
        readonly string $bearerToken,
        readonly string $pageId,
    ) {
    }
}
