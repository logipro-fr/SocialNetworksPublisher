<?php

namespace SocialNetworksPublisher\Infrastructure\Provider\Exceptions;

use SocialNetworksPublisher\Domain\Model\Post\Exceptions\LoggedException;

class UnauthorizedException extends LoggedException
{
    public const ERROR_CODE = 401;
}
