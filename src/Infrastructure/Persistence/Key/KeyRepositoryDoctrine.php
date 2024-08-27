<?php

namespace SocialNetworksPublisher\Infrastructure\Persistence\Key;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use SocialNetworksPublisher\Domain\Model\Key\Exceptions\KeyAlreadyExistsException;
use SocialNetworksPublisher\Domain\Model\Key\Exceptions\KeyNotFoundException;
use SocialNetworksPublisher\Domain\Model\Key\Key;
use SocialNetworksPublisher\Domain\Model\Key\KeyId;
use SocialNetworksPublisher\Domain\Model\Key\KeyRepositoryInterface;

/**
 * @extends EntityRepository<Key>
 */
class KeyRepositoryDoctrine extends EntityRepository implements KeyRepositoryInterface
{
    public function __construct(EntityManagerInterface $em)
    {
        $class = $em->getClassMetadata(Key::class);
        parent::__construct($em, $class);
    }
    public function add(Key $key): void
    {
        $this->getEntityManager()->persist($key);
    }

    public function findById(KeyId $keyId): Key
    {
        $key = $this->getEntityManager()->find(Key::class, $keyId);
        if ($key == null) {
            throw new KeyNotFoundException($keyId);
        }
        return $key;
    }
}
