<?php

namespace SocialNetworksPublisher\Application\Service\Key\AddPage;

class AddPageRequest
{
    public function __construct(
        readonly string $keyId,
        readonly string $pageId
    ) {
    }
}
