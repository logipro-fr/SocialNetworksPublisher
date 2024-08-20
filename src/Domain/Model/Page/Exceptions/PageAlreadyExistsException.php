<?php

namespace SocialNetworksPublisher\Domain\Model\Page\Exceptions;

use SocialNetworksPublisher\Domain\Model\Post\Exceptions\LoggedException;

class PageAlreadyExistsException extends LoggedException {
    public const MESSAGE = "Page '%s' already exists";
    public const ERROR_CODE = 409;

    public function __construct(
        string $pageId
    ) {
        parent::__construct(sprintf(self::MESSAGE, $pageId), self::ERROR_CODE);
    }
}