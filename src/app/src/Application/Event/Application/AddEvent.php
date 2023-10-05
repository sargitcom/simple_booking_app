<?php

namespace App\Application\Event\Application;

use App\Application\Event\Domain\CreateEventEvent;
use App\Application\EventStore\Application\InsertDomainEvent;

class AddEvent
{
    public function __construct(private InsertDomainEvent $insertDomainEvent) {}

    public function createEvent(AddEventRequest $request) : AddEventResponse
    {
        $domainEvent = new CreateEventEvent($request->getEventId(), $request->getEventName());

        $this->insertDomainEvent->insertEvent($domainEvent);

        return new AddEventResponse($request->getEventId(), $request->getEventName());
    }
}