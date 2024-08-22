<?php

namespace SocialNetworksPublisher\Domain\Model\Key;

use SocialNetworksPublisher\Domain\Model\Key\Exceptions\KeyDataEmptyBearerTokenException;
use SocialNetworksPublisher\Domain\Model\Key\Exceptions\KeyLinkedInDataEmptyUrnException;

class LinkedInKeyData extends AbstractKeyData {
    public function __construct(protected string $bearerToken, private string $urn) {
        $this->checkBearerToken();
        $this->checkUrn();
    }

    public function getUrn(): string {
        return $this->urn;
    }

    private function checkUrn(): void {
        if (empty($this->urn)) {
            throw new KeyLinkedInDataEmptyUrnException();
        }
    }

    public function checkBearerToken(): void {
        if (empty($this->bearerToken)) {
            throw new KeyDataEmptyBearerTokenException();
        }
    }
}