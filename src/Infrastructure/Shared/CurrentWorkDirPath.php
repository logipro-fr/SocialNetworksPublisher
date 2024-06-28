<?php

namespace SocialNetworksPublisher\Infrastructure\Shared;

use SocialNetworksPublisher\Infrastructure\Exceptions\NoPWDException;

class CurrentWorkDirPath
{
    public static function getPath(): string
    {
        return dirname(__DIR__, 3);
    }
}
