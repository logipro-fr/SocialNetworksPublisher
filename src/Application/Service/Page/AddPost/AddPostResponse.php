<?php

namespace SocialNetworksPublisher\Application\Service\Page\AddPost;

class AddPostResponse
{
    public function __construct(
        readonly string $postId,
    ) {
    }
}
