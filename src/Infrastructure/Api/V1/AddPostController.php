<?php

namespace SocialNetworksPublisher\Infrastructure\Api\V1;

use Doctrine\ORM\EntityManagerInterface;
use SocialNetworksPublisher\Application\Service\Page\AddPost\AddPost;
use SocialNetworksPublisher\Application\Service\Page\AddPost\AddPostRequest;
use SocialNetworksPublisher\Domain\Model\Page\PageRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddPostController extends AbstractController
{
    public function __construct(
        private PageRepositoryInterface $pages,
        private EntityManagerInterface $em,
    ) {
    }
    #[Route("api/v1/pages/post", name:"pages_post", methods:["PATCH"])]
    public function execute(Request $request): Response
    {
        try {
            $addPostRequest = $this->convertToAddPostRequest($request);
            $service = new AddPost($this->pages);
            $service->execute($addPostRequest);
            $eventFlush = new EventFlush($this->em);
            $eventFlush->flushAndDistribute();
            return $this->writeSuccessfulResponse($service->getResponse(), Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->writeUnsuccessfulResponse($e);
        }
    }

    public function convertToAddPostRequest(Request $request): AddPostRequest
    {
        /** @var string $content */
        $content = $request->getContent();
        /** @var \stdClass $requestObject */
        $requestObject = json_decode($content);
        return new AddPostRequest(
            $requestObject->pageId,
            $requestObject->content,
        );
    }
}
