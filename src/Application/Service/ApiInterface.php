<?php

namespace SocialNetworksPublisher\Application\Service;

use SocialNetworksPublisher\Application\Service\PublishPost\PublishPostResponse;
use SocialNetworksPublisher\Domain\Model\Post\Post;

interface ApiInterface
{
    public function postApiRequest(Post $post): PublishPostResponse;
}
