<?php

namespace SocialNetworksPublisher\Infrastructure\Provider;

use SocialNetworksPublisher\Application\Service\PublishPost\ProviderResponse;
use SocialNetworksPublisher\Application\Service\PublishPost\SocialNetworksApiInterface;
use SocialNetworksPublisher\Domain\Model\Page\Post;

class NullApiProvider implements SocialNetworksApiInterface
{
    public function postApiRequest(Post $post): ProviderResponse
    {
        return new ProviderResponse(false);
    }
}
