<?php

namespace SocialNetworksPublisher\Tests\Domain\Model\Post\Exceptions;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use SocialNetworksPublisher\Domain\Model\Post\Author;
use SocialNetworksPublisher\Domain\Model\Post\Exceptions\BadAuthorIdException;
use SocialNetworksPublisher\Domain\Model\Post\Exceptions\LoggedException;
use SocialNetworksPublisher\Infrastructure\Shared\CurrentWorkDirPath;

use function Safe\file_get_contents;
use function Safe\unlink;

class LoggedExceptionTest extends TestCase
{
    private string $logFilePath;
    private string $logDirPath;

    protected function setUp(): void
    {
        $this->logFilePath = CurrentWorkDirPath::getPath() . LoggedException::LOG_FILE_PATH;
        $this->logDirPath = dirname($this->logFilePath);
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

    public function testEnsureLogDirectoryExists(): void
    {
        $loggedExceptionTester = new LoggedExceptionTester("message", 0);

        if (is_dir($this->logDirPath)) {
            unlink($this->logFilePath);
            rmdir($this->logDirPath);
        }

        $loggedExceptionTester->publicEnsureLogDirectoryExists($this->logDirPath);

        $this->assertDirectoryExists($this->logDirPath);

        $message = "a logged exception";
        $code = 1;

        $mockedException = $this->getMockBuilder(LoggedException::class)
                                ->setConstructorArgs([$message, $code])
                                ->onlyMethods(['ensureLogDirectoryExists', 'ensureLogFileExists'])
                                ->getMock();

        $mockedException->expects($this->once())
                        ->method('ensureLogDirectoryExists')
                        ->with($this->equalTo($this->logDirPath));

        $mockedException->expects($this->once())
                        ->method('ensureLogFileExists')
                        ->with($this->equalTo($this->logFilePath));

        /** @var ReflectionClass<LoggedException> */
        $reflection = new \ReflectionClass($mockedException);
        /** @var ReflectionMethod */
        $constructor = $reflection->getConstructor();
        $constructor->invoke($mockedException, $message, $code);
    }

    public function testEnsureLogFileExists(): void
    {
        $loggedExceptionTester = new LoggedExceptionTester("message", 0);

        if (file_exists($this->logFilePath)) {
            unlink($this->logFilePath);
        }

        $loggedExceptionTester->publicEnsureLogFileExists($this->logFilePath);

        $this->assertFileExists($this->logFilePath);
    }
}
