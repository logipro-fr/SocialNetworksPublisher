<?php

namespace SocialNetworksPublisher\Tests\Domain;

use PHPUnit\Framework\TestCase;

use SocialNetworksPublisher\Domain\Exceptions\BadSocialNetworksParameterException;
use SocialNetworksPublisher\Domain\Exceptions\BadPageIdException;
use SocialNetworksPublisher\Domain\Page;


class PageTest extends TestCase
{
    public function testValidPage(): void
    {
        $page = new Page("faceBook", "123456");
        $this->assertEquals("facebook", $page->getSocialNetwork());
        $this->assertEquals("123456", $page->getId());
        $page = new Page("LinkedIn", "456");
        $this->assertEquals("linkedin", $page->getSocialNetwork());
        $this->assertEquals("456", $page->getId());
    }

    public function testBadPageSocialNetworksException(): void
    {
        $this->expectException(BadSocialNetworksParameterException::class);
        $this->expectExceptionMessage("The social network parameters cannot be empty");
        new Page("", "456");
    }

    public function testBadPageId() : void {
        $this->expectException(BadPageIdException::class);
        $this->expectExceptionMessage("The id parameters cannot be empty");
        new Page("facebook", "");
    }
}
