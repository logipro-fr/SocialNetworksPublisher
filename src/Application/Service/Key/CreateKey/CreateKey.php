<?php

namespace SocialNetworksPublisher\Application\Service\Key\CreateKey;

use Safe\DateTimeImmutable;
use SocialNetworksPublisher\Application\Service\Key\CreateKey\Exceptions\InvalidCreateKeyRequest;
use SocialNetworksPublisher\Domain\Model\Key\AbstractKeyData;
use SocialNetworksPublisher\Domain\Model\Key\Identity;
use SocialNetworksPublisher\Domain\Model\Key\Key;
use SocialNetworksPublisher\Domain\Model\Key\KeyId;
use SocialNetworksPublisher\Domain\Model\Key\KeyRepositoryInterface;
use SocialNetworksPublisher\Domain\Model\Key\LinkedInKeyData;
use SocialNetworksPublisher\Domain\Model\Key\TwitterKeyData;
use SocialNetworksPublisher\Domain\Model\Shared\SocialNetworks;

class CreateKey
{
    private CreateKeyResponse $response;
    public function __construct(
        private KeyRepositoryInterface $keys
    ) {
    }

    public function execute(AbstractCreateKeyRequest $request): void
    {
        $key = $this->keyFactory($request);
        $this->keys->add($key);
        $this->response = new CreateKeyResponse(
            $key->getKeyId()
        );
    }

    public function getResponse(): CreateKeyResponse
    {
        return $this->response;
    }

    private function createKeyData(AbstractCreateKeyRequest $request): AbstractKeyData
    {
        return match (true) {
            $request instanceof CreateKeyTwitterRequest => new TwitterKeyData(
                $request->bearerToken,
                $request->refreshToken
            ),
            $request instanceof CreateKeyLinkedInRequest => new LinkedInKeyData(
                $request->bearerToken,
                $request->urn
            ),
            default => throw new InvalidCreateKeyRequest()
        };
    }

    private function determineSocialNetwork(AbstractCreateKeyRequest $request): SocialNetworks
    {
        return match (true) {
            $request instanceof CreateKeyTwitterRequest => SocialNetworks::Twitter,
            $request instanceof CreateKeyLinkedInRequest => SocialNetworks::LinkedIn,
            default => throw new InvalidCreateKeyRequest()
        };
    }

    private function keyFactory(AbstractCreateKeyRequest $request): Key {
        $keyData = $this->createKeyData($request);
        $socialNetwork = $this->determineSocialNetwork($request);
        $key = new Key(
            $id = new KeyId(),
            $socialNetwork,
            new DateTimeImmutable(),
            $keyData,
            new Identity($request->pageId)
        );
        return $key;
    }
}
