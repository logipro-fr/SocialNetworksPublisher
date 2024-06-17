<?php

namespace SocialNetworksPublisher\Infrastructure\Provider\Facebook;

use SocialNetworksPublisher\Application\Service\SocialNetworksApiInterface;
use SocialNetworksPublisher\Domain\Model\Post\Post;
use SocialNetworksPublisher\Infrastructure\Provider\ProviderResponse;

class Facebook implements SocialNetworksApiInterface
{
    public function postApiRequest(Post $post): ProviderResponse
    {
        return new ProviderResponse($post->getPostId(), "facebook");
    }
}
