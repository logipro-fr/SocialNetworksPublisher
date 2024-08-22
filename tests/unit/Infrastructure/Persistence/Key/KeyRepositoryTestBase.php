<?php

namespace SocialNetworksPublisher\Tests\Infrastructure\Persistence\Key;

use PHPUnit\Framework\TestCase;
use Safe\DateTimeImmutable;
use SocialNetworksPublisher\Domain\Model\Key\Exceptions\KeyAlreadyExistsException;
use SocialNetworksPublisher\Domain\Model\Key\Exceptions\KeyNotFoundException;
use SocialNetworksPublisher\Domain\Model\Key\Key;
use SocialNetworksPublisher\Domain\Model\Key\KeyId;
use SocialNetworksPublisher\Domain\Model\Key\KeyRepositoryInterface;
use SocialNetworksPublisher\Domain\Model\Key\TwitterKeyData;
use SocialNetworksPublisher\Domain\Model\Shared\SocialNetworks;

abstract class KeyRepositoryTestBase extends TestCase
{
    protected KeyRepositoryInterface $keys;
    public function testAddAndFind(): void
    {
        $keyId = new KeyId();
        $sut = new Key(
            $keyId,
            SocialNetworks::Twitter,
            new DateTimeImmutable(),
            new TwitterKeyData("test", "test")
        );

        $this->keys->add($sut);
        $foundPage = $this->keys->findById($keyId);
        $this->assertTrue($sut->getKeyId()->equals($foundPage->getKeyId()));
    }

    public function testKeyNotFoundException(): void
    {
        $this->expectException(KeyNotFoundException::class);
        $this->expectExceptionCode(KeyNotFoundException::ERROR_CODE);
        $this->expectExceptionMessage(sprintf(KeyNotFoundException::MESSAGE, "test"));

        $this->keys->findById(new KeyId("test"));
    }
}
