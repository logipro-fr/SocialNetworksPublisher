<?php

namespace SocialNetworksPublisher\Tests\Domain\Model\Post;

use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Domain\Model\Post\Exceptions\BadSocialNetworksParameterException;
use SocialNetworksPublisher\Domain\Model\Post\Exceptions\BadPageIdException;
use SocialNetworksPublisher\Domain\Model\Post\Page;

class PageTest extends TestCase
{
    public function testValidPage(): void
    {
        $page = new Page("123456");
        $page2 = new Page("456");
        $this->assertEquals("123456", $page->getId());
        $this->assertEquals("456", $page2->getId());
    }

    public function testBadPageId(): void
    {
        $this->expectException(BadPageIdException::class);
        $this->expectExceptionCode(BadPageIdException::ERROR_CODE);
        $this->expectExceptionMessage("The id parameters cannot be empty");
        new Page("");
    }
}
