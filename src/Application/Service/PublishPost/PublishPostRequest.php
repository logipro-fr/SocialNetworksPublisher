<?php

namespace SocialNetworksPublisher\Application\Service\PublishPost;

class PublishPostRequest
{
    public function __construct(
        public readonly string $socialNetworks,
        public readonly string $authorId,
        public readonly string $pageId,
        public readonly string $content,
        public readonly string $hashtag,
    ) {
    }
}
