<?php

namespace SocialNetworksPublisher\Tests\Infrastructure\Provider\SimpleBlog;

use SocialNetworksPublisher\Infrastructure\Provider\SimpleBlog\SimpleBlog;

class SimpleBlogFake extends SimpleBlog
{
    public function verifyFilePath(string $filePath): void
    {
        parent::verifyFilePath($filePath);
    }
}
