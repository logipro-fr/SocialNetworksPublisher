<?php

namespace SocialNetworksPublisher\Infrastructure\Api\V1;

use Doctrine\ORM\EntityManagerInterface;
use SocialNetworksPublisher\Application\Service\Page\CreatePage\CreatePage;
use SocialNetworksPublisher\Application\Service\Page\CreatePage\CreatePageRequest;
use SocialNetworksPublisher\Domain\Model\Page\PageRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreatePageController extends AbstractController
{
    public function __construct(
        private PageRepositoryInterface $pages,
        private EntityManagerInterface $em,
    ) {
    }
    #[Route("api/v1/pages", name:"pages", methods:["POST"])]
    public function execute(Request $request): Response
    {
        try {
            $createPageRequest = $this->convertToPageRequest($request);
            $service = new CreatePage($this->pages);
            $service->execute($createPageRequest);
            $this->em->flush();
            return $this->writeSuccessfulResponse($service->getResponse(), Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->writeUnsuccessfulResponse($e);
        }
    }

    public function convertToPageRequest(Request $request): CreatePageRequest
    {
        /** @var string $content */
        $content = $request->getContent();
        /** @var \stdClass $requestObject */
        $requestObject = json_decode($content);
        return new CreatePageRequest(
            $requestObject->pageName,
            $requestObject->socialNetwork,
        );
    }
}
