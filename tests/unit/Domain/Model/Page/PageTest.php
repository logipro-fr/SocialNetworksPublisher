<?php

namespace SocialNEtworksPublisher\Tests\Domain\Model\Page;

use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Domain\Model\Page\Page;
use SocialNetworksPublisher\Domain\Model\Page\PageId;
use SocialNetworksPublisher\Domain\Model\Page\PageName;
use SocialNetworksPublisher\Domain\Model\Post\PostId;
use SocialNetworksPublisher\Domain\Model\Shared\SocialNetworks;

class PageTest extends TestCase
{
    public function testCreatePage(): void
    {
        $sut = new Page(
            new PageId("page_id"),
            new PageName("page_name"),
            SocialNetworks::Twitter
        );

        $this->assertTrue((new PageId("page_id"))->equals($sut->getPageId()));
        $this->assertEquals("page_name", $sut->getName());
        $this->assertEquals(SocialNetworks::Twitter, $sut->getSocialNetwork());
        $this->assertEmpty($sut->getPostIds());
    }

    public function addPost(): void
    {
        $sut = new Page(
            new PageId("page_id"),
            new PageName("page_name"),
            SocialNetworks::Twitter
        );

        $postId = new PostId("postId");

        $sut->addPost($postId);

        $this->assertEquals($postId, $sut->getPostIds()[0]);
    }
}
