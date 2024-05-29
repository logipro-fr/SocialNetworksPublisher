<?php

namespace SocialNetworksPublisher\Infrastructure\Api\V1;

use Doctrine\ORM\EntityManagerInterface;
use SocialNetworksPublisher\Application\Service\ApiInterface;
use SocialNetworksPublisher\Application\Service\PublishPost\PublishPost;
use SocialNetworksPublisher\Application\Service\PublishPost\PublishPostRequest;
use SocialNetworksPublisher\Domain\Model\Post\Exceptions\LoggedException;
use SocialNetworksPublisher\Domain\Model\Post\PostRepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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
        $publishRequest = $this->buildPublishRequest($request);

        $service = new PublishPost($this->api, $this->repo);
        try {
            $service->execute($publishRequest);
            $this->entityManager->flush();
        } catch (\Throwable $e) {
            $fullClassName = get_class($e);
            $className = (new \ReflectionClass($e))->getShortName();
            return new JsonResponse(
                [
                    'succes' => false,
                    'ErrorCode' => $className,
                    'data' => '',
                    'message' => $e->getMessage(),
                ],
                $e->getCode(),
            );
        }
        $publishResponse = $service->getResponse();
        return new JsonResponse(
            [
                'success' => true,
                'ErrorCode' => "",
                'data' => ['postId' =>  $publishResponse->postId,
                            'socialNetworks' => $publishResponse->socialNetworks],
                'message' => "",
            ],
            201
        );
    }

    private function buildPublishRequest(Request $request): PublishPostRequest
    {
        $content = $request->getContent();
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
        // /** @var string */
        // $socialNetworks = $request->get("socialNetworks");
        // /** @var string */
        // $authorId = $request->get("authorId");
        // /** @var string */
        // $pageId = $request->get("pageId");
        // /** @var string */
        // $content = $request->get("content");
        // /** @var string */
        // $hashtag = $request->get("hashtag");
        return new PublishPostRequest($socialNetworks, $authorId, $pageId, $content, $hashtag);
    }
}
