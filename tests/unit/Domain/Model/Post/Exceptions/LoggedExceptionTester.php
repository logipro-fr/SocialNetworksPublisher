<?php
namespace SocialNetworksPublisher\Tests\Domain\Model\Post\Exceptions;

use SocialNetworksPublisher\Domain\Model\Post\Exceptions\LoggedException;

class LoggedExceptionTester extends LoggedException
{
    public function publicEnsureLogDirectoryExists(string $directoryPath): void
    {
        $this->ensureLogDirectoryExists($directoryPath);
    }

    public function publicEnsureLogFileExists(string $filePath): void
    {
        $this->ensureLogFileExists($filePath);
    }
}
