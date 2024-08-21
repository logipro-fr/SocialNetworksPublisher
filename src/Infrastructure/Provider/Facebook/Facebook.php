<?php

namespace SocialNetworksPublisher\Infrastructure\Provider\Facebook;

use SocialNetworksPublisher\Application\Service\PublishPost\SocialNetworksApiInterface;
use SocialNetworksPublisher\Domain\Model\Page\Post;
use SocialNetworksPublisher\Application\Service\PublishPost\ProviderResponse;

class Facebook implements SocialNetworksApiInterface
{
    public function postApiRequest(Post $post): ProviderResponse
    {
        return new ProviderResponse(false);
    }
}
