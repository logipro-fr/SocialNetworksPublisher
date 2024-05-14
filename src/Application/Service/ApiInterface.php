<?php

namespace SocialNetworksPublisher\Application\Service;

use SocialNetworksPublisher\Application\Service\PublishPost\PublishPostResponse;
use SocialNetworksPublisher\Domain\Post;

interface ApiInterface
{
    public function postApiRequest(Post $post): PublishPostResponse;
}
