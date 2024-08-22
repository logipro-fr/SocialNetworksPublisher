<?php

namespace SocialNetworksPublisher\Application\Service\Key\CreateKey;

class CreateKeyLinkedInRequest extends AbstractCreateKeyRequest
{
    public function __construct(
        string $bearerToken,
        readonly string $urn
    ) {
        parent::__construct($bearerToken);
    }
}
