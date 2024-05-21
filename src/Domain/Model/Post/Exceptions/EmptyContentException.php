<?php

namespace SocialNetworksPublisher\Domain\Model\Post\Exceptions;

use Exception;

class EmptyContentException extends LoggedException
{
    public const MESSAGE =
     "An EmptyContentException has occurred: Unable to perform operation due to empty content.";
     public const ERROR_CODE = 411;
}
