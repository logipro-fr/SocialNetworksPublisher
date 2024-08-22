<?php

namespace SocialNetworksPublisher\Domain\Model\Key;

use SocialNetworksPublisher\Domain\Model\Key\Exceptions\KeyDataEmptyBearerTokenException;
use SocialNetworksPublisher\Domain\Model\Key\Exceptions\KeyTwitterDataEmptyRefreshTokenException;

class TwitterKeyData extends AbstractKeyData
{
    public function __construct(protected string $bearerToken, private string $refreshToken)
    {
        $this->checkBearerToken();
        $this->checkRefreshToken();
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    public function setRefreshToken(string $refreshToken): void
    {
        $this->refreshToken = $refreshToken;
    }

    protected function checkBearerToken(): void
    {
        if (empty($this->bearerToken)) {
            throw new KeyDataEmptyBearerTokenException();
        }
    }

    private function checkRefreshToken(): void
    {
        if (empty($this->refreshToken)) {
            throw new KeyTwitterDataEmptyRefreshTokenException();
        }
    }
}
