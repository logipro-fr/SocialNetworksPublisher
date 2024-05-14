<?php

namespace SocialNetworksPublisher\Domain\Exceptions;

use Exception;

class EmptyContentException extends Exception
{
    public const MESSAGE =
     "An EmptyContentException has occurred: Unable to perform operation due to empty content.";
}
