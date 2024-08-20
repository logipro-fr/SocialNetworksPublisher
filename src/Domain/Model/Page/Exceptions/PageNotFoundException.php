<?php

namespace SocialNetworksPublisher\Domain\Model\Page\Exceptions;

use SocialNetworksPublisher\Domain\Model\Post\Exceptions\LoggedException;

class PageNotFoundException extends LoggedException {
    public const MESSAGE = "Page '%s' not found";
    public const ERROR_CODE = 404;

    public function __construct(
        string $pageId
    ) {
        parent::__construct(sprintf(self::MESSAGE, $pageId), self::ERROR_CODE);
    }
}