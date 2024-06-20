<?php

namespace SocialNetworksPublisher\Infrastructure\Provider\Twitter;

use SocialNetworksPublisher\Application\Service\PublishPost\ProviderResponse;
use SocialNetworksPublisher\Application\Service\PublishPost\SocialNetworksApiInterface;
use SocialNetworksPublisher\Domain\Model\Post\Post;

class Twitter implements SocialNetworksApiInterface
{
    public function postApiRequest(Post $post): ProviderResponse
    {
        return new ProviderResponse(true);
    }
}
