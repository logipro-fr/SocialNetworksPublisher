<?php

namespace SocialNetworksPublisher\Tests\Domain\Model\Post\Exceptions;

use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Domain\Model\Post\Author;
use SocialNetworksPublisher\Domain\Model\Post\Exceptions\BadAuthorIdException;
use SocialNetworksPublisher\Domain\Model\Post\Exceptions\LoggedException;
use SocialNetworksPublisher\Infrastructure\Shared\CurrentWorkDirPath;

use function Safe\file_get_contents;
use function Safe\unlink;

class LoggedExceptionTest extends TestCase
{
    private string $logFilePath;
    protected function setUp(): void
    {
        $this->logFilePath = CurrentWorkDirPath::getPath() . LoggedException::LOG_FILE_PATH;
    }

    public function testLoggedException(): void
    {
        $this->deleteLogFile();
        new BadAuthorIdException("Log test", 0);

        $logs = file_get_contents($this->logFilePath);
        $this->assertStringEndsWith("0: Log test\n", $logs);
    }

    public function testCheckCanCreateLogFile(): void
    {
        $this->deleteLogFile();

        $sut = new LoggedException("a logged exception", 1);

        $this->assertFileExists($this->logFilePath);
    }

    public function isExceptionThrowed(): void
    {
        $this->expectException(LoggedException::class);
        new Author("df", "");
    }

    private function deleteLogFile(): void
    {
        if (file_exists($this->logFilePath)) {
            unlink($this->logFilePath);
        }
    }
}
