<?php

namespace SocialNetworks\Domain;

use PHPUnit\Framework\TestCase;
use SocialNetworks\Domain\Exceptions\EmptyTargetStatusException;

class TargetStatusTest extends TestCase
{
    public function testTargetStatus(): void
    {
        $targetStatus = new TargetStatus(Status::PUBLISHED);

        $this->assertEquals(Status::PUBLISHED->value, $targetStatus->getTargetStatus());
        $this->assertNotEquals(Status::DRAFT, $targetStatus->getTargetStatus());
    }
}
