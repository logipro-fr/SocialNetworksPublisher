<?php

namespace SocialNetworksPublisher\Domain\Model\Key;

interface KeyRepositoryInterface
{
    public function add(Key $key): void;

    public function findById(KeyId $keyId): Key;
}
