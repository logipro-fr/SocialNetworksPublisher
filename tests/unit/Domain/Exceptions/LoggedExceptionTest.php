<?php

namespace SocialNetworksPublisher\Tests\Domain\Model\Post\Exceptions;

use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Domain\Model\Post\Exceptions\BadAuthorIdException;
use SocialNetworksPublisher\Domain\Model\Post\Exceptions\LoggedException;
use SocialNetworksPublisher\Infrastructure\Shared\CurrentWorkDirPath;

use function Safe\file_get_contents;

class LoggedExceptionTest extends TestCase {
    public function testLoggedException(): void {
        $filePath = CurrentWorkDirPath::getPath() . LoggedException::LOG_FILE_PATH;
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        try {
            throw new BadAuthorIdException("Log test", 0);
        } catch (LoggedException $e) {
            $logs = file_get_contents($filePath);
            $this->assertStringEndsWith("0: Log test\n", $logs);
        }
    }

}