<?php

namespace SocialNetworksPublisher\Domain\Model\Key;

abstract class AbstractKeyData
{
    protected string $bearerToken;
    abstract protected function checkBearerToken(): void;

    public function getBearerToken(): string
    {
        return $this->bearerToken;
    }

    public function setBearerToken(string $bearerToken): void
    {
        $this->bearerToken = $bearerToken;
    }
}
