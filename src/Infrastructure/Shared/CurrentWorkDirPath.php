<?php

namespace SocialNetworksPublisher\Infrastructure\Shared;

use SocialNetworksPublisher\Infrastructure\Exceptions\NoPWDException;

class CurrentWorkDirPath
{
    public static function getPath(): string
    {
        $dir = __DIR__;
        if (!empty($dir)) {
            return dirname(__DIR__, 3);
        }


        throw new NoPWDException("Environment variable PWD not found");
    }
}
