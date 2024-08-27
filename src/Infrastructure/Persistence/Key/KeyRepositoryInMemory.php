<?php

namespace SocialNetworksPublisher\Infrastructure\Persistence\Key;

use SocialNetworksPublisher\Domain\Model\Key\Exceptions\KeyAlreadyExistsException;
use SocialNetworksPublisher\Domain\Model\Key\Exceptions\KeyNotFoundException;
use SocialNetworksPublisher\Domain\Model\Key\Key;
use SocialNetworksPublisher\Domain\Model\Key\KeyId;
use SocialNetworksPublisher\Domain\Model\Key\KeyRepositoryInterface;

class KeyRepositoryInMemory implements KeyRepositoryInterface
{
    /** @var array<string, Key> */
    private array $keys = [];
    public function add(Key $key): void
    {
        if (array_key_exists($id = $key->getKeyId()->__toString(), $this->keys)) {
            throw new KeyAlreadyExistsException($id);
        }
        $this->keys[$key->getKeyId()->__toString()] = $key;
    }

    public function findById(KeyId $keyId): Key
    {
        if (!array_key_exists($id = $keyId->__toString(), $this->keys)) {
            throw new KeyNotFoundException($id);
        }

        return $this->keys[$id];
    }
}
