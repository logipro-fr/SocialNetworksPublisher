<?php

namespace SocialNetworksPublisher\Domain\Model\Post\Exceptions;

use Exception;

class BadSocialNetworksParameterException extends LoggedException
{
    public const ERROR_CODE = 422;
}
