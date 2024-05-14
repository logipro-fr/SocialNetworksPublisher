<?php

namespace SocialNetworks\Application\Service\PublishPost;

class PublishPostRequest
{
    public function __construct(
        public readonly string $authorName,
        public readonly string $authorType,
        public readonly string $authorId,
        public readonly string $pageName,
        public readonly string $pageId,
        public readonly string $content,
        public readonly string $hashtag,
        public readonly string $targetStatus,
    ) {
    }
}
