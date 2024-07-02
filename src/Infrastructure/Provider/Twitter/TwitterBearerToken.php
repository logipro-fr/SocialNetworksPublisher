<?php

namespace SocialNetworksPublisher\Infrastructure\Provider\Twitter;

use DateTime;
use SocialNetworksPublisher\Infrastructure\Shared\CurrentWorkDirPath;

use function Safe\file_get_contents;
use function Safe\file_put_contents;

class TwitterBearerToken
{
    public const BEARER_PATH = "/var/BearerToken.txt";
    public const REFRESH_PATH = "/var/RefreshToken.txt";
    public const EXPIRATION_PATH = "/var/TokenExpiration.txt";

    private string $bearerPath;
    private string $refreshPath;
    private string $expirationPath;

    public function __construct(
        string $bearerPath = self::BEARER_PATH,
        string $refreshPath = self::REFRESH_PATH,
        string $expirationPath = self::EXPIRATION_PATH
    ) {
        $this->bearerPath = CurrentWorkDirPath::getPath() . $bearerPath;
        $this->refreshPath = CurrentWorkDirPath::getPath() . $refreshPath;
        $this->expirationPath = CurrentWorkDirPath::getPath() . $expirationPath;
    }

    public function getBearerToken(): string
    {
        return file_get_contents($this->bearerPath);
    }

    public function getRefreshToken(): string
    {
        return file_get_contents($this->refreshPath);
    }

    public function getRefreshPath(): string
    {
        return $this->refreshPath;
    }

    public function getExpirationDate(): DateTime
    {
        return new DateTime(file_get_contents($this->expirationPath));
    }

    public function setBearerToken(string $token, DateTime $expirationDate): void
    {
        file_put_contents($this->bearerPath, $token);
        file_put_contents($this->expirationPath, $expirationDate->format(DateTime::ATOM));
    }

    public function setRefreshToken(string $token): void
    {
        file_put_contents($this->refreshPath, $token);
    }

    public function needsRefresh(): bool
    {
        $expirationDate = $this->getExpirationDate();
        $currentDate = new DateTime();
        if ($currentDate >= $expirationDate->modify('+2 hours')) {
            return true;
        }
        return false;
    }
}
