<?php

namespace SocialNetworksPublisher\Domain\Model\Post\Exceptions;

use Exception;

class BadPageIdException extends LoggedException
{
    public const ERROR_CODE = 413;
}
