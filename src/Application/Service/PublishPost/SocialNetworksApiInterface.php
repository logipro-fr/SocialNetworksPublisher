<?php

namespace SocialNetworksPublisher\Application\Service\PublishPost;

use SocialNetworksPublisher\Domain\Model\Page\Post;
use SocialNetworksPublisher\Application\Service\PublishPost\ProviderResponse;

interface SocialNetworksApiInterface
{
    public function postApiRequest(Post $post): ProviderResponse;
}
