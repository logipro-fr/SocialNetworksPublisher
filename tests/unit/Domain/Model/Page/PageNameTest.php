<?php

namespace SocialNetworksPublisher\Tests\Domain\Model\Page;

use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Domain\Model\Page\PageName;

class PageNameTest extends TestCase
{
    public function testPageName(): void
    {
        $sut = new PageName("my_page_name");
        $this->assertEquals("my_page_name", $sut);
    }

    // public function testEmptyPageNameException(): void {
    //     $this->expectException(EmptyPageNameException::class);
    //     $this->expectExceptionCode();
    //     $this->expectExceptionMessage("An empty pageName has been fournissed");
    //     new PageName("");
    // }
}
