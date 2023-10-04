<?php

namespace App\Application\EventStore\Application;

use App\Application\EventStore\Domain\DomainEvent;
use App\Application\EventStore\Domain\EventStoreRepository;

class InsertDomainEvent
{
    private EventStoreRepository $eventStoreRepository;

    public function __construct(EventStoreRepository $eventStoreRepository)
    {
        $this->eventStoreRepository = $eventStoreRepository;
    }

    public function insertEvent(DomainEvent $event) : void
    {
        $eventId = $this->eventStoreRepository->getNextIdentifier();
        $this->eventStoreRepository->save($event->toEventStore($eventId));
    }
}
