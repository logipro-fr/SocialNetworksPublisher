<?php

namespace SocialNetworksPublisher\Domain\Model\Shared\Exceptions;

use SocialNetworksPublisher\Domain\Model\Shared\Exceptions\LoggedException;

class SocialNetworksDoesntExist extends LoggedException
{
    public const MESSAGE = "Page social network '%s' not found";
    public const ERROR_CODE = 404;

    public function __construct(
        string $socialNetwork
    ) {
        parent::__construct(sprintf(self::MESSAGE, $socialNetwork), self::ERROR_CODE);
    }
}
