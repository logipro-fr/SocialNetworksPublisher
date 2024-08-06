<?php

namespace SocialNetworksPublisher\Domain\Model\Post\Exceptions;

class PostNotFoundException extends LoggedException
{
    public const ERROR_CODE = 500;
}
