<?php

namespace App\Application\Event\Domain;

use App\Application\EventStore\Domain\DomainEvent;
use App\Application\EventStore\Domain\DomainEventBody;
use Symfony\Component\Uid\Uuid;

class CreateEventEvent extends DomainEvent
{
    private EventName $eventName;

    public function __construct(Uuid $aggregateId, EventName $eventName)
    {
        parent::__construct($aggregateId);

        $this->eventName = $eventName;
    }

    protected function getData() : DomainEventBody
    {
        return DomainEventBody::create(
            [
                'eventName' => $this->eventName->getEventName(),
            ]
        );
    }
}
