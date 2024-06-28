<?php

namespace SocialNetworksPublisher\Infrastructure\Shared;

class CurrentWorkDirPath
{
    public static function getPath(): string
    {
        return dirname(__DIR__, 3);
    }
}
