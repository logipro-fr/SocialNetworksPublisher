<?php

namespace SocialNetworksPublisher\Infrastructure\Provider\LinkedIn;

use SocialNetworksPublisher\Application\Service\PublishPost\SocialNetworksApiInterface;
use SocialNetworksPublisher\Domain\Model\Post\Post;
use SocialNetworksPublisher\Application\Service\PublishPost\ProviderResponse;

class LinkedIn implements SocialNetworksApiInterface
{
    public function postApiRequest(Post $post): ProviderResponse
    {
        return new ProviderResponse(false);
    }
}
