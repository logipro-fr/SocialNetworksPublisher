<?php

namespace SocialNetworksPublisher\Infrastructure\Api\V1;

use Doctrine\ORM\EntityManagerInterface;
use SocialNetworksPublisher\Application\Service\Key\AddPage\AddPage;
use SocialNetworksPublisher\Application\Service\Key\AddPage\AddPageRequest;
use SocialNetworksPublisher\Domain\Model\Key\KeyRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use function Safe\json_decode;

class AddPageController extends AbstractController
{
    public function __construct(
        private KeyRepositoryInterface $keys,
        private EntityManagerInterface $em
    ) {
    }
    #[Route("api/v1/keys/page", name:"keys_page", methods:["PATCH"])]
    public function execute(Request $request): Response
    {
        try {
            $addPageRequest = $this->convertToAddPageRequest($request);
            $service = new AddPage($this->keys);
            $service->execute($addPageRequest);
            $this->em->flush();
            return $this->writeSuccessfulResponse($service->getResponse(), Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->writeUnsuccessfulResponse($e);
        }
    }

    private function convertToAddPageRequest(Request $request): AddPageRequest
    {
        /** @var string $content */
        $content = $request->getContent();
        /** @var \stdClass */
        $requestObject = json_decode($content);
        return new AddPageRequest(
            $requestObject->keyId,
            $requestObject->pageId,
        );
    }
}
