<?php

namespace SocialNetworksPublisher\Tests\Infrastructure\Persistence\Doctrine\Types;

use SocialNetworksPublisher\Domain\Model\Key\AbstractKeyData;

class FakeKeyData extends AbstractKeyData {
    public function __construct(protected string $bearerToken)
    {
    }
    protected function checkBearerToken(): void
    {

    }
}