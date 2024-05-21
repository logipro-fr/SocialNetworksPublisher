<?php

namespace SocialNetworksPublisher\Domain\Model\Post\Exceptions;

use Exception;

class BadAuthorIdException extends LoggedException
{
    public const ERROR_CODE = 406;
}
