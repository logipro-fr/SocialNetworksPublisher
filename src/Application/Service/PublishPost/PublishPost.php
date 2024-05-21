<?php

namespace SocialNetworksPublisher\Application\Service\PublishPost;

use SocialNetworksPublisher\Application\Service\ApiInterface;
use SocialNetworksPublisher\Domain\Model\Post\Post;
use SocialNetworksPublisher\Domain\Model\Post\PostRepositoryInterface;

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
        $this->repository->add($post);
        $apiResponse = $this->api->postApiRequest($post);
        $this->response = new PublishPostResponse(
            $apiResponse->success,
            $apiResponse->statusCode,
            $apiResponse->data,
            ""
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
