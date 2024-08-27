<?php

namespace SocialNetworksPublisher\Tests\Application\Service\Key\CreateKey;

use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Application\Service\Key\CreateKey\CreateKey;
use SocialNetworksPublisher\Application\Service\Key\CreateKey\CreateKeyLinkedInRequest;
use SocialNetworksPublisher\Application\Service\Key\CreateKey\CreateKeyTwitterRequest;
use SocialNetworksPublisher\Application\Service\Key\CreateKey\Exceptions\InvalidCreateKeyRequest;
use SocialNetworksPublisher\Domain\Model\Key\Key;
use SocialNetworksPublisher\Domain\Model\Key\KeyId;
use SocialNetworksPublisher\Domain\Model\Key\LinkedInKeyData;
use SocialNetworksPublisher\Domain\Model\Key\TwitterKeyData;
use SocialNetworksPublisher\Domain\Model\Shared\SocialNetworks;
use SocialNetworksPublisher\Infrastructure\Persistence\Key\KeyRepositoryInMemory;

class CreateKeyTest extends TestCase
{
    public function testCreateKeyTwitter(): void
    {
        $keys = new KeyRepositoryInMemory();
        $service = new CreateKey($keys);
        $service->execute(
            $request = new CreateKeyTwitterRequest(
                "bearer_token",
                "refresh_token",
                "pageId"
            )
        );
        $response = $service->getResponse();
        /** @var Key */
        $foundKey = $keys->findById(new KeyId($response->keyId));

        $this->assertInstanceOf(TwitterKeyData::class, $foundKey->getKeyData());
        $this->assertEquals(SocialNetworks::Twitter, $foundKey->getSocialNetwork());
        $this->assertEquals("pageId", $foundKey->getValue());
    }

    public function testCreateKeyLinkedIn(): void
    {
        $keys = new KeyRepositoryInMemory();
        $service = new CreateKey($keys);
        $service->execute(
            $request = new CreateKeyLinkedInRequest(
                "bearer_token",
                "urn",
                "pageId"
            )
        );
        $response = $service->getResponse();
        /** @var Key */
        $foundKey = $keys->findById(new KeyId($response->keyId));

        $this->assertInstanceOf(LinkedInKeyData::class, $foundKey->getKeyData());
        $this->assertEquals(SocialNetworks::LinkedIn, $foundKey->getSocialNetwork());
    }

    public function testPageSocialNetworksDoesntExist(): void
    {
        $this->expectException(InvalidCreateKeyRequest::class);
        $this->expectExceptionCode(InvalidCreateKeyRequest::ERROR_CODE);
        $this->expectExceptionMessage(sprintf(InvalidCreateKeyRequest::MESSAGE));

        $keys = new KeyRepositoryInMemory();
        $service = new CreateKey($keys);
        $service->execute(new CreateKeyFakeRequest("bearer", "pageId"));
    }
}
