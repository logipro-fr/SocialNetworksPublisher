<?php

namespace SocialNetworksPublisher\Application\Service\Page\CreatePage;

class CreatePageRequest
{
    public function __construct(
        readonly string $pageName,
        readonly string $socialNetworks
    ) {
    }
}
