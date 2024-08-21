<?php

namespace SocialNetworksPublisher\Application\Service\PublishPost;

class PublishPostRequest
{
    public function __construct(

        public readonly string $content,
    ) {
    }
}
