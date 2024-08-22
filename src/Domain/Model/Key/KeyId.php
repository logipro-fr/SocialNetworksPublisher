<?php

namespace SocialNetworksPublisher\Domain\Model\Key;

class KeyId
{
    public const PREFIX = "key_";
    public function __construct(private string $id = "")
    {
        if (empty($id)) {
            $this->id = uniqid(self::PREFIX);
        }
    }

    public function equals(KeyId $otherKey): bool
    {
        if ($this->id !== $otherKey->id) {
            return false;
        }
        return true;
    }

    public function __toString()
    {
        return $this->id;
    }
}
