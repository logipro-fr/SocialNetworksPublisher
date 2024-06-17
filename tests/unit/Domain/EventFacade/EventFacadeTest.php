<?php

namespace SocialNetworksPublisher\Tests\Domain\EventFacade;

use Phariscope\Event\EventDispatcher;
use Phariscope\Event\Tools\SpyListener;
use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Domain\EventFacade\EventFacade;

class EventFacadeTest extends TestCase
{
    public function testSubscribe(): void
    {
        $listener = new SpyListener();
        $sut = new EventFacade();

        $sut->subscribe($listener);

        $this->assertTrue(EventDispatcher::instance()->hasSubscriber($listener));
    }

    public function testDispatchEvent(): void
    {
        $event = new EventFake("unId");
        $spy = new SpyListener();
        $sut = new EventFacade();
        $sut->subscribe($spy);

        $sut->dispatch($event);
        $sut->distribute();

        $this->assertInstanceOf(EventFake::class, $spy->domainEvent);
    }
}
