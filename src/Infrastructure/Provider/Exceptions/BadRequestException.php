<?php

namespace SocialNetworksPublisher\Infrastructure\Provider\Exceptions;

use SocialNetworksPublisher\Domain\Model\Shared\Exceptions\LoggedException;
use Symfony\Component\HttpFoundation\Response;

class BadRequestException extends LoggedException
{
    public const ERROR_CODE = Response::HTTP_BAD_REQUEST;
}
