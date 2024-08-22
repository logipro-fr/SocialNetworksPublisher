<?php

namespace SocialNetworksPublisher\Infrastructure\Api\V1;

use Doctrine\ORM\EntityManagerInterface;
use SocialNetworksPublisher\Application\Service\PublishPost\AbstractFactorySocialNetworksApi;
use SocialNetworksPublisher\Application\Service\PublishPost\SocialNetworksApiInterface;
use SocialNetworksPublisher\Application\Service\PublishPost\PublishPost;
use SocialNetworksPublisher\Application\Service\PublishPost\PublishPostRequest;
use SocialNetworksPublisher\Application\Service\PublishPost\PublishPostResponse;
use SocialNetworksPublisher\Domain\EventFacade\EventFacade;
use SocialNetworksPublisher\Domain\Model\Post\PostRepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

use function Safe\json_decode;

class PublisherController
{
}
