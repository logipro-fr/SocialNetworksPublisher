<?php

namespace SocialNetworksPublisher\Domain\Model\Key;

use Safe\DateTimeImmutable;
use SebastianBergmann\Type\NullType;
use SocialNetworksPublisher\Domain\Model\Page\PageId;
use SocialNetworksPublisher\Domain\Model\Shared\SocialNetworks;

class Key
{
    private Identity $value;
    public function __construct(
        private KeyId $keyId,
        private SocialNetworks $socialNetworks,
        private DateTimeImmutable $expirationTime,
        private AbstractKeyData $keyData,
    ) {
        $this->value = new Identity(new PageId("null"));
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

    public function getValue(): Identity
    {
        return $this->value;
    }

    public function setExpirationDateTime(DateTimeImmutable $expirationTime): void
    {
        $this->expirationTime = $expirationTime;
    }

    public function setIdentity(PageId $value): void
    {
        $this->value = new Identity($value);
    }
}
