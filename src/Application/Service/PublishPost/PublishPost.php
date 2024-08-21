<?php

namespace SocialNetworksPublisher\Application\Service\PublishPost;

use SocialNetworksPublisher\Application\Service\PublishPost\SocialNetworksApiInterface;
use SocialNetworksPublisher\Domain\Model\Page\Post;
use SocialNetworksPublisher\Domain\Model\Page\PostId;
use SocialNetworksPublisher\Domain\Model\Post\PostRepositoryInterface;
use PostStatus;

class PublishPost
{
    private PublishPostResponse $response;
    public function __construct(
        private AbstractFactorySocialNetworksApi $socialNetworksFactory,

        private string $postIdName = ""
    ) {
    }
    public function execute(PublishPostRequest $request): void
    {
    }

    public function getResponse(): PublishPostResponse
    {
        return $this->response;
    }
}
