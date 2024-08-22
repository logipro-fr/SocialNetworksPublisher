<?php

namespace SocialNetworksPublisher\Domain\Model\Key\Exceptions;

use SocialNetworksPublisher\Domain\Model\Shared\Exceptions\LoggedException;

class KeyTwitterDataEmptyRefreshTokenException extends LoggedException {
    public const MESSAGE = "Empty key refresh token";
    public const ERROR_CODE = 404;

    public function __construct(
    ) {
        parent::__construct(self::MESSAGE, self::ERROR_CODE);
    }
}