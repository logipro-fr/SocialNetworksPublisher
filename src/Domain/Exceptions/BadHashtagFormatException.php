<?php

namespace SocialNetworks\Domain\Exceptions;

use Exception;

class BadHashtagFormatException extends Exception
{
    public const MESSAGE =
        "Error: Missing or invalid hashtag format.";
}
