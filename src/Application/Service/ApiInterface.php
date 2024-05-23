<?php

namespace SocialNetworksPublisher\Application\Service;

use SocialNetworksPublisher\Domain\Model\Post\Post;
use SocialNetworksPublisher\Infrastructure\Provider\ProviderResponse;

interface ApiInterface
{
    public function postApiRequest(Post $post): ProviderResponse;
}
