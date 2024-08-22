<?php

namespace SocialNetworksPublisher\Infrastructure\Provider\Exceptions;

use SocialNetworksPublisher\Domain\Model\Shared\Exceptions\LoggedException;

class InvalidSocialNetworks extends LoggedException
{
    public const ERROR_CODE = 500;
}
