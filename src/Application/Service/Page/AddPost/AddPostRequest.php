<?php

namespace SocialNetworksPublisher\Application\Service\Page\AddPost;

class AddPostRequest
{
    public function __construct(
        readonly string $pageId,
        readonly string $postContent
    ) {
    }
}
