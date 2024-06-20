<?php

namespace SocialNetworksPublisher\Infrastructure\Shared;

use SocialNetworksPublisher\Infrastructure\Exceptions\NoPWDException;

class CurrentWorkDirPath
{
    public static function getPath(): string
    {
        if (isset($_ENV["PWD"])) {
            return $_ENV["PWD"];
        }

        $pwdFromEnv = getenv('PWD');
        if ($pwdFromEnv !== false) {
            return $pwdFromEnv;
        }

        throw new NoPWDException("Environment variable PWD not found");
    }
}
