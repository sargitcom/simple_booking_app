<?php

namespace App\Application\Event\Domain;

use App\Application\EventStore\Domain\DomainEvent;
use App\Application\EventStore\Domain\DomainEventBody;
use DateTime;
use Symfony\Component\Uid\Uuid;

class EventDayReservedEvent extends DomainEvent
{
    protected EventDaySeats $seats;
    protected Uuid $eventId;
    protected DateTime $date;

    public function __construct(
        Uuid $aggregateId, 
        EventDaySeats $seats,
        Uuid $eventId,
        DateTime $date,
    ) {
        parent::__construct($aggregateId);

        $this->seats = $seats;
        $this->eventId = $eventId;
        $this->date = $date;

        $this->version = AgreggateVersion::create();
    }

    protected function getData() : DomainEventBody
    {
        return DomainEventBody::create(
            [
                'seats' => $this->seats->getSeatsNumber(),
                'eventId' => $this->eventId->toRfc4122(),
                'year' => $this->date->format('Y'),
                'month' => $this->date->format('m'),
                'day' => $this->date->format('d'),
            ]
        );
    }
}
