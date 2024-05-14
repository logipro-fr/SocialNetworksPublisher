<?php

namespace SocialNetworks\Application\Service;

use SocialNetworks\Application\Service\PublishPost\PublishPostResponse;
use SocialNetworks\Domain\Post;

interface ApiInterface
{
    public function postApiRequest(Post $post): PublishPostResponse;
}
