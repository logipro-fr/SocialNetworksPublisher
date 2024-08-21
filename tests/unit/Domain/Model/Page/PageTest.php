<?php

namespace SocialNEtworksPublisher\Tests\Domain\Model\Page;

use Phariscope\Event\EventDispatcher;
use Phariscope\Event\Tools\SpyListener;
use PHPUnit\Framework\TestCase;
use Safe\DateTimeImmutable;
use SocialNetworksPublisher\Domain\Model\Page\Event\PageCreated;
use SocialNetworksPublisher\Domain\Model\Page\Page;
use SocialNetworksPublisher\Domain\Model\Page\PageId;
use SocialNetworksPublisher\Domain\Model\Page\PageName;
use SocialNetworksPublisher\Domain\Model\Page\Post;
use SocialNetworksPublisher\Domain\Model\Page\PostStatus;
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
        $this->assertEmpty($sut->getPosts());
    }

    public function testAddPost(): void
    {
        $sut = new Page(
            new PageId("page_id"),
            new PageName("page_name"),
            SocialNetworks::Twitter
        );

        $post = new Post(
            "content",
            PostStatus::READY
        );

        $sut->addPost($post);

        $this->assertEquals($post, $sut->getPosts()[0]);
    }

    public function testCreatedAt(): void
    {
        $now = new DateTimeImmutable();
        $sut = new Page(
            new PageId("page_id"),
            new PageName("page_name"),
            SocialNetworks::Twitter
        );
        $timeWindow = $now->diff($sut->getCreatedAt(), true);
        $this->assertTrue($timeWindow->s < 2);
    }

    public function testCreateEventEmitted(): void
    {
        $spy = new SpyListener();
        EventDispatcher::instance()->subscribe($spy);
        $sut = new Page(
            new PageId("page_test_creation"),
            new PageName("page_name"),
            SocialNetworks::Twitter
        );
        EventDispatcher::instance()->distribute();
        $event = $spy->domainEvent;
        $this->assertInstanceOf(PageCreated::class, $event);
        /** @var PageCreated $event */
        $this->assertEquals("page_test_creation", $event->pageId);

        $eventEmittedTime = $event->occurredOn();
        $accCreatedTime = $sut->getCreatedAt();
        $this->assertTrue($accCreatedTime->diff($eventEmittedTime, true)->s < 2);
    }
}
