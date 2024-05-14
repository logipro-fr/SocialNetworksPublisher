<?php

namespace SocialNetworks\Tests\Domain;

use PHPUnit\Framework\TestCase;
use SocialNetworks\Domain\Author;
use SocialNetworks\Domain\Content;
use SocialNetworks\Domain\Exceptions\BadSocialNetworksParameterException;
use SocialNetworks\Domain\Exceptions\EmptyPageIdException;
use SocialNetworks\Domain\Exceptions\BadPageIdException;
use SocialNetworks\Domain\HashTag;
use SocialNetworks\Domain\Page;
use SocialNetworks\Domain\PageId;
use SocialNetworks\Domain\Post;
use SocialNetworks\Domain\PostId;
use SocialNetworks\Domain\Status;
use SocialNetworks\Domain\TargetStatus;

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
