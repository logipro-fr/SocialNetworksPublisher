<?php

namespace SocialNetworksPublisher\Tests\Domain\Model\Post;

use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Domain\Model\Post\Exceptions\BadSocialNetworksParameterException;
use SocialNetworksPublisher\Domain\Model\Post\SocialNetworks;

class SocialNetworksTest extends TestCase
{
    public function testSocialNetworksEnum(): void
    {
        $this->assertEquals("Facebook", SocialNetworks::Facebook->value);
        $this->assertEquals("LinkedIn", SocialNetworks::LinkedIn->value);
        $this->assertEquals("SimpleBlog", SocialNetworks::SimpleBlog->value);
    }

    public function testBadSocialNetworksException(): void
    {
        $this->expectException(BadSocialNetworksParameterException::class);
        $this->expectExceptionCode(BadSocialNetworksParameterException::ERROR_CODE);
        $this->expectExceptionMessage("Invalid social network");
        SocialNetworks::fromString("test");
    }
}
