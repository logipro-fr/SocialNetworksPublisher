<?php

namespace SocialNetworksPublisher\Tests\Domain\Model\Post;

use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Domain\Model\Shared\Exceptions\SocialNetworksDoesntExist;
use SocialNetworksPublisher\Domain\Model\Shared\SocialNetworks;

class SocialNetworksTest extends TestCase
{
    public function testSocialNetworksEnum(): void
    {
        $this->assertEquals("Facebook", SocialNetworks::Facebook->value);
        $this->assertEquals("LinkedIn", SocialNetworks::LinkedIn->value);
        $this->assertEquals("SimpleBlog", SocialNetworks::SimpleBlog->value);
        $this->assertEquals("Twitter", SocialNetworks::Twitter->value);
    }

    public function testFromString(): void
    {
        $this->assertEquals(SocialNetworks::Facebook, SocialNetworks::fromString('Facebook'));
        $this->assertEquals(SocialNetworks::LinkedIn, SocialNetworks::fromString('LinkedIn'));
        $this->assertEquals(SocialNetworks::SimpleBlog, SocialNetworks::fromString('SimpleBlog'));
        $this->assertEquals(SocialNetworks::Twitter, SocialNetworks::fromString('Twitter'));
    }

    public function testPageSocialNetworksDoesntExist(): void
    {
        $this->expectException(SocialNetworksDoesntExist::class);
        $this->expectExceptionCode(SocialNetworksDoesntExist::ERROR_CODE);
        $this->expectExceptionMessage(sprintf("Page social network '%s' not found", "test"));
        SocialNetworks::fromString("test");
    }
}
