<?php

namespace SocialNetworksPublisher\Tests\Domain\Model\Post;

use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Domain\Model\Post\SocialNetworks;

class SocialNetworksTest extends TestCase {
    public function testSocialNetworksEnum(): void {
        $this->assertEquals("Facebook", SocialNetworks::Facebook->value);
        $this->assertEquals("LinkedIn", SocialNetworks::LinkedIn->value);
        $this->assertEquals("SimpleBlog", SocialNetworks::SimpleBlog->value);
    }
}