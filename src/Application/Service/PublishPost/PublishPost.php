<?php

namespace SocialNetworksPublisher\Application\Service\PublishPost;

use SocialNetworksPublisher\Application\Service\ApiInterface;
use SocialNetworksPublisher\Domain\Model\Post\Post;
use SocialNetworksPublisher\Domain\Model\Post\PostId;
use SocialNetworksPublisher\Domain\Model\Post\PostRepositoryInterface;
use SocialNetworksPublisher\Domain\Model\Post\Status;

class PublishPost
{
    private PublishPostResponse $response;
    public function __construct(
        private ApiInterface $api,
        private PostRepositoryInterface $repository,
        private string $postIdName = ""
    ) {
    }
    public function execute(PublishPostRequest $request): void
    {
        $post = $this->createPost($request);
        $apiResponse = $this->api->postApiRequest($post);
        $this->repository->add($post);
        $post->setStatus(Status::PUBLISHED);

        $this->response = new PublishPostResponse(
            $apiResponse->postId,
            $apiResponse->socialNetworks,
        );
    }

    private function createPost(PublishPostRequest $request): Post
    {
        $postFactory = new PostFactory();
        return $postFactory->buildPostFromRequest($request, $this->postIdName);
    }

    public function getResponse(): PublishPostResponse
    {
        return $this->response;
    }
}
