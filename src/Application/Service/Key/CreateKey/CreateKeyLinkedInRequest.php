<?php

namespace SocialNetworksPublisher\Application\Service\Key\CreateKey;

class CreateKeyLinkedInRequest extends AbstractCreateKeyRequest
{
    public function __construct(
        string $bearerToken,
        readonly string $urn,
        string $pageId,
    ) {
        parent::__construct($bearerToken, $pageId);
    }
}
