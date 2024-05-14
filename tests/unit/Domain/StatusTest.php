<?php

namespace SocialNetworksPublisher\Tests\Domain;

use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Domain\Status;

class StatusTest extends TestCase
{
    public function testStatus(): void
    {
        $this->assertInstanceOf(Status::class, Status::READY);
        $this->assertInstanceOf(Status::class, Status::DRAFT);
    }
}
