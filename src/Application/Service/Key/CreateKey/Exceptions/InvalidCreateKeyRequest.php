<?php

namespace SocialNetworksPublisher\Application\Service\Key\CreateKey\Exceptions;

use SocialNetworksPublisher\Domain\Model\Shared\Exceptions\LoggedException;

class InvalidCreateKeyRequest extends LoggedException
{
    public const MESSAGE = "Invalid key create key request";
    public const ERROR_CODE = 404;

    public function __construct()
    {
        parent::__construct(sprintf(self::MESSAGE), self::ERROR_CODE);
    }
}
