<?php

namespace SocialNetworksPublisher\Domain\Model\Post\Exceptions;

use Exception;

class BadHashtagFormatException extends LoggedException
{
    public const MESSAGE =
        "Error: Missing or invalid hashtag format.";

    public const ERROR_CODE = 500;
}
