<?php
namespace SocialNetworksPublisher\Tests\Infrastructure\Persistence\Doctrine\Types;

use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Infrastructure\Persistence\Doctrine\Types\HashTagArrayType;

class HashTagArrayTypeTest extends TestCase {
    public function testGetName(): void {
        $this->assertEquals('hashTagArray', (new HashTagArrayType())->getName());
    }
}