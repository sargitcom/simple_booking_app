<?php

namespace App\Application\Event\Domain;

use App\Application\EventStore\Application\InsertDomainEvent;
use DateTime;
use Symfony\Component\Uid\Uuid;
use Throwable;

class ReservationService
{
    public function __construct(private InsertDomainEvent $insertDomainEvent) {}

    public function reserveEventDays(
        Uuid $eventId,
        Uuid $reservationId,
        DateTime $startDate,
        DateTime $endDate,
        int $seatsNumber
    ) : void {
        try {
            $this->createReservation(
                $eventId,
                $reservationId,
                $startDate,
                $endDate,
                $seatsNumber
            );
        } catch (Throwable) {
            throw new CantSaveReservationException();
        }
    }

    protected function createReservation(
        Uuid $eventId, 
        Uuid $reservationId,
        DateTime $startDate, 
        DateTime $endDate, 
        int $seatsNumber
    ) {
        $reservedSeats = EventDaySeats::create($seatsNumber);

        $event = new CreateReservationEvent(
            $reservationId,
            $eventId,
            $startDate,
            $endDate,
            $reservedSeats
        );

        $this->insertDomainEvent->insertEvent($event);
    }
}
