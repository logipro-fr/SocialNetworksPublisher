<?php

namespace SocialNetworksPublisher\Tests\Application\Service\Key\AddKey;

use PHPUnit\Framework\TestCase;
use Safe\DateTimeImmutable;
use SocialNetworksPublisher\Application\Service\Key\AddPage\AddPage;
use SocialNetworksPublisher\Application\Service\Key\AddPage\AddPageRequest;
use SocialNetworksPublisher\Domain\Model\Key\Key;
use SocialNetworksPublisher\Domain\Model\Key\KeyId;
use SocialNetworksPublisher\Domain\Model\Key\TwitterKeyData;
use SocialNetworksPublisher\Domain\Model\Page\Page;
use SocialNetworksPublisher\Domain\Model\Shared\SocialNetworks;
use SocialNetworksPublisher\Infrastructure\Persistence\Key\KeyRepositoryInMemory;

class AddKeyTest extends TestCase
{
    public function testAddKey(): void
    {
        $keys = new KeyRepositoryInMemory();
        $key = new Key(
            new KeyId("key_id"),
            SocialNetworks::Twitter,
            new DateTimeImmutable(),
            new TwitterKeyData("a", "b")
        );
        $keys->add($key);
        $request = new AddPageRequest(
            "key_id",
            "page_id"
        );

        $service = new AddPage($keys);

        $service->execute($request);

        $response = $service->getResponse();
        /** @var Key */
        $foundKey = $keys->findById(new KeyId($response->keyId));
        $this->assertEquals("page_id", $foundKey->getValue());
    }
}
