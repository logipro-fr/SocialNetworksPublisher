<?php

namespace SocialNetworksPublisher\Infrastructure\Api\V1;

use SocialNetworksPublisher\Application\Service\ApiInterface;
use SocialNetworksPublisher\Application\Service\PublishPost\PublishPost;
use SocialNetworksPublisher\Application\Service\PublishPost\PublishPostRequest;
use SocialNetworksPublisher\Domain\Model\Post\PostRepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PublisherController
{
    public function __construct(
        private ApiInterface $api,
        private PostRepositoryInterface $repo
    ) {
    }
    #[Route('/api/v1/publishPost', "publish_post", methods: ['GET'])]
    public function execute(Request $request): JsonResponse
    {
        $publishRequest = $this->buildPublishRequest($request);
        $service = new PublishPost($this->api, $this->repo);
        $service->execute($publishRequest);
        $publishResponse = $service->getResponse();
        return new JsonResponse(
            [
                'success' => $publishResponse->success,
                'statusCode' => $publishResponse->statusCode,
                'data' => $publishResponse->data,
                'message' => $publishResponse->message,
            ],
            $publishResponse->statusCode
        );
    }

    private function buildPublishRequest(Request $request): PublishPostRequest
    {
        $content = $request->getContent();
        /** @var string */
        $socialNetworks = $request->get("socialNetworks");
        /** @var string */
        $authorId = $request->get("authorId");
        /** @var string */
        $pageId = $request->get("pageId");
        /** @var string */
        $content = $request->get("content");
        /** @var string */
        $hashtag = $request->get("hashtag");
        return new PublishPostRequest($socialNetworks, $authorId, $pageId, $content, $hashtag);
    }
}
