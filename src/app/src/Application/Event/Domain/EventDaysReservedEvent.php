<?php

namespace App\Application\Event\Domain;

use App\Application\EventStore\Domain\DomainEvent;
use App\Application\EventStore\Domain\DomainEventBody;
use Symfony\Component\Uid\Uuid;

class EventDaysReservedEvent extends DomainEvent
{
    protected EventName $eventName;

    public function __construct(Uuid $aggregateId, EventName $eventName)
    {
        parent::__construct($aggregateId);

        $this->eventName = $eventName;
        $this->version = AgreggateVersion::create();
    }

    protected function getData() : DomainEventBody
    {
        return DomainEventBody::create(
            [
                'eventName' => $this->eventName->getEventName(),
                'version' => $this->version->getVersion(),
            ]
        );
    }
}
