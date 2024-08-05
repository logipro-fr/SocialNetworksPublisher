<?php

namespace SocialNetworksPublisher\Infrastructure\Provider\Exceptions;

use SocialNetworksPublisher\Domain\Model\Post\Exceptions\LoggedException;
use Symfony\Component\HttpFoundation\Response;

class DuplicatePostException extends LoggedException
{
    public const ERROR_CODE = Response::HTTP_FORBIDDEN;
}
