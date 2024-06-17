<?php

namespace SocialNetworksPublisher\Infrastructure\Provider\LinkedIn;

use SocialNetworksPublisher\Application\Service\SocialNetworksApiInterface;
use SocialNetworksPublisher\Domain\Model\Post\Post;
use SocialNetworksPublisher\Infrastructure\Provider\ProviderResponse;

class LinkedIn implements SocialNetworksApiInterface
{
    public function postApiRequest(Post $post): ProviderResponse
    {
        return new ProviderResponse($post->getPostId(), "linkedIn");
    }
}
