<?php

namespace SocialNetworksPublisher\Domain\Model\Post\Exceptions;

use Exception;

class EmptyParametersException extends LoggedException
{
    public const ERROR_CODE = 400;
}
