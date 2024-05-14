<?php

namespace SocialNetworks\Tests\Domain;

use PHPUnit\Framework\TestCase;
use SocialNetworks\Domain\Status;

class StatusTest extends TestCase
{
    public function testStatus(): void
    {
        $this->assertInstanceOf(Status::class, Status::PUBLISHED);
        $this->assertInstanceOf(Status::class, Status::DRAFT);
    }
}
