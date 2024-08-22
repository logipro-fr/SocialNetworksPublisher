<?php

namespace SocialNetworksPublisher\Domain\Model\Key\Exceptions;

use SocialNetworksPublisher\Domain\Model\Shared\Exceptions\LoggedException;

class KeyNotFoundException extends LoggedException
{
    public const MESSAGE = "Key '%s' not found";
    public const ERROR_CODE = 404;

    public function __construct(
        string $keyId
    ) {
        parent::__construct(sprintf(self::MESSAGE, $keyId), self::ERROR_CODE);
    }
}
