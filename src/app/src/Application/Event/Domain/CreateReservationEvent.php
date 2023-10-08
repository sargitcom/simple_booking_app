<?php

namespace App\Application\Event\Domain;

use App\Application\EventStore\Domain\DomainEvent;
use App\Application\EventStore\Domain\DomainEventBody;
use DateTime;
use Symfony\Component\Uid\Uuid;

class CreateReservationEvent extends DomainEvent
{
    protected Uuid $eventId;
    protected DateTime $startDate;
    protected DateTime $endDate;
    protected EventDaySeats $seatsNumber;

    public function __construct(
        Uuid $reservationId,
        Uuid $eventId,
        DateTime $startDate,
        DateTime $endDate,
        EventDaySeats $seatsNumber
    ) {
        parent::__construct($reservationId);

        $this->eventId = $eventId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->seatsNumber = $seatsNumber;
        $this->version = AgreggateVersion::create();
    }

    protected function getData() : DomainEventBody
    {
        return DomainEventBody::create(
            [
                'eventId' => $this->eventId,
                'startDate' => $this->startDate->format('Y-m-d'),
                'endDate' => $this->endDate->format('Y-m-d'),
                'seatsNumber' => $this->seatsNumber->getSeatsNumber(),
                'version' => $this->version->getVersion(),
            ]
        );
    }
}
