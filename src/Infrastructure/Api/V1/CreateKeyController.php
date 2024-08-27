<?php

namespace SocialNetworksPublisher\Infrastructure\Api\V1;

use Doctrine\ORM\EntityManagerInterface;
use SocialNetworksPublisher\Application\Service\Key\CreateKey\CreateKey;
use SocialNetworksPublisher\Application\Service\Key\CreateKey\CreateKeyTwitterRequest;
use SocialNetworksPublisher\Domain\Model\Key\KeyRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateKeyController extends AbstractController
{
    public function __construct(
        private KeyRepositoryInterface $keys,
        private EntityManagerInterface $em,
    ) {
    }
    #[Route("api/v1/keys/twitter", name:"keys_twitter", methods:["POST"])]
    public function execute(Request $request): Response
    {
        try {
            $createKeyRequest = $this->convertToTwitterCreateKeyRequest($request);
            $service = new CreateKey($this->keys);
            $service->execute($createKeyRequest);
            $eventFlush = new EventFlush($this->em);
            $eventFlush->flushAndDistribute();
            return $this->writeSuccessfulResponse($service->getResponse(), Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->writeUnsuccessfulResponse($e);
        }
    }

    public function convertToTwitterCreateKeyRequest(Request $request): CreateKeyTwitterRequest
    {
        /** @var string $content */
        $content = $request->getContent();
        /** @var \stdClass $requestObject */
        $requestObject = json_decode($content);
        return new CreateKeyTwitterRequest(
            $requestObject->bearerToken,
            $requestObject->refreshToken,
            $requestObject->pageId,
        );
    }
}
