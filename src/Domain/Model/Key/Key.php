<?php

namespace SocialNetworksPublisher\Domain\Model\Key;

use Safe\DateTimeImmutable;
use SocialNetworksPublisher\Domain\Model\Shared\SocialNetworks;

class Key
{
    public function __construct(
        private SocialNetworks $socialNetworks,
        private DateTimeImmutable $expirationTime,
        private AbstractKeyData $keyData,
        private KeyId $keyId = new KeyId(),
    ) {
    }

    public function getKeyId(): KeyId
    {
        return $this->keyId;
    }

    public function getSocialNetwork(): SocialNetworks
    {
        return $this->socialNetworks;
    }

    public function getExpirationDate(): DateTimeImmutable
    {
        return $this->expirationTime;
    }

    public function getKeyData(): AbstractKeyData
    {
        return $this->keyData;
    }

    public function setExpirationDateTime(DateTimeImmutable $expirationTime): void
    {
        $this->expirationTime = $expirationTime;
    }
}
