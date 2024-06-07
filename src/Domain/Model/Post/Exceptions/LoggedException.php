<?php

namespace SocialNetworksPublisher\Domain\Model\Post\Exceptions;

use Safe\DateTime;
use SocialNetworksPublisher\Infrastructure\Shared\CurrentWorkDirPath;

use function Safe\error_log;
use function Safe\fclose;
use function Safe\file_put_contents;
use function Safe\link;
use function Safe\mkdir;

class LoggedException extends \Exception
{
    public const LOG_FILE_PATH = "/log/exceptions.log";
    private const LOG_PATTERN = "[%s] %d: %s\n";

    public function __construct(string $message, int $code)
    {
        parent::__construct($message, $code);

        $logFilePath = CurrentWorkDirPath::getPath() . self::LOG_FILE_PATH;
        $this->ensureLogDirectoryExists(dirname($logFilePath));
        $this->ensureLogFileExists($logFilePath);
        error_log($this->getMessageInFormat($message, $code), 3, $logFilePath);
    }

    private function ensureLogDirectoryExists(string $directoryPath): void
    {
        if (!is_dir($directoryPath)) {
            mkdir($directoryPath, 0755, true);
        }
    }

    private function ensureLogFileExists(string $filePath): void
    {
        if (!file_exists($filePath)) {
            $fileHandle = fopen($filePath, 'c+b');
            if ($fileHandle !== false) {
                fclose($fileHandle);
            }
        }
    }

    private function getMessageInFormat(string $message, int $code): string
    {
        return sprintf(self::LOG_PATTERN, (new DateTime())->format("d.m.Y H:i:s"), $code, $message);
    }
}
