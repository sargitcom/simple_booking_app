<?php

namespace App\Application\Event\Domain;

use App\Application\EventStore\Domain\DomainEvent;
use App\Application\EventStore\Domain\DomainEventBody;
use Symfony\Component\Uid\Uuid;

class UpdateEventEvent extends DomainEvent
{
    private int $version = 1;
    private EventName $eventName;

    public function __construct(Uuid $aggregateId, EventName $eventName, int $version = 1)
    {
        parent::__construct($aggregateId);

        $this->eventName = $eventName;
    }

    protected function getData() : DomainEventBody
    {
        return DomainEventBody::create(
            [
                'eventName' => $this->eventName->getEventName(),
                'version' => $this->getVersion(),
            ]
        );
    }
}
