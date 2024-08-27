<?php

namespace SocialNetworksPublisher\Infrastructure\Api\V1;

use Doctrine\ORM\EntityManagerInterface;
use SocialNetworksPublisher\Domain\EventFacade\EventFacade;

class EventFlush
{
    public function __construct(
        private EntityManagerInterface $em,
        private EventFacade $eventFacade = new EventFacade()
    ) {
    }

    public function flushAndDistribute(): void
    {
        $this->em->flush();
        try {
            $this->eventFacade->distribute();
        } catch (\Exception $e) {
        }
        $this->em->flush();
    }
}