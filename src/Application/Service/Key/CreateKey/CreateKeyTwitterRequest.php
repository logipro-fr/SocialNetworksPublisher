<?php

namespace SocialNetworksPublisher\Application\Service\Key\CreateKey;

class CreateKeyTwitterRequest extends AbstractCreateKeyRequest
{
    public function __construct(
        string $bearerToken,
        readonly string $refreshToken
    ) {
        parent::__construct($bearerToken);
    }
}
