<?php

namespace SocialNetworksPublisher\Infrastructure\Api\V1;

use Doctrine\ORM\EntityManagerInterface;
use SocialNetworksPublisher\Application\Service\ApiInterface;
use SocialNetworksPublisher\Application\Service\PublishPost\PublishPost;
use SocialNetworksPublisher\Application\Service\PublishPost\PublishPostRequest;
use SocialNetworksPublisher\Application\Service\PublishPost\PublishPostResponse;
use SocialNetworksPublisher\Domain\Model\Post\PostRepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

use function Safe\json_decode;

class PublisherController
{
    public function __construct(
        private ApiInterface $api,
        private PostRepositoryInterface $repo,
        private EntityManagerInterface $entityManager
    ) {
    }

    #[Route('/api/v1/post/publish', "publish_post", methods: ['POST'])]
    public function execute(Request $request): JsonResponse
    {
        return $this->handleRequest(function () use ($request) {
            $publishRequest = $this->buildPublishRequest($request);
            $service = new PublishPost($this->api, $this->repo);
            $service->execute($publishRequest);
            $publishResponse = $service->getResponse();
            $this->entityManager->flush();
            return $this->writeSuccessfulResponse($publishResponse);
        });
    }

    private function handleRequest(callable $function): JsonResponse
    {
        try {
            return $function();
        } catch (Throwable $e) {
            return $this->writeUnSuccessFulResponse($e);
        }
    }

    private function writeSuccessfulResponse(PublishPostResponse $publishResponse): JsonResponse
    {
        return new JsonResponse(
            [
                'success' => true,
                'ErrorCode' => "",
                'data' => [
                    'postId' => $publishResponse->postId,
                    'socialNetworks' => $publishResponse->socialNetworks
                ],
                'message' => "",
            ],
            201
        );
    }

    private function writeUnSuccessFulResponse(Throwable $e): JsonResponse
    {
        $className = (new \ReflectionClass($e))->getShortName();
        return new JsonResponse(
            [
                'success' => false,
                'ErrorCode' => $className,
                'data' => '',
                'message' => $e->getMessage(),
            ],
            $e->getCode() ?: 500,
        );
    }

    private function buildPublishRequest(Request $request): PublishPostRequest
    {
        /** @var string */
        $content = $request->getContent();
        /** @var array<string> */
        $data = json_decode($content, true);

        /** @var string */
        $socialNetworks = $data['socialNetworks'];
        /** @var string */
        $authorId = $data['authorId'];
        /** @var string */
        $pageId = $data['pageId'];
        /** @var string */
        $content = $data['content'];
        /** @var string */
        $hashtag = $data['hashtag'];

        return new PublishPostRequest($socialNetworks, $authorId, $pageId, $content, $hashtag);
    }
}
