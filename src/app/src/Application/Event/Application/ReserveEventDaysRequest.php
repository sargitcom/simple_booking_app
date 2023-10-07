<?php

namespace App\Application\Event\Application;

use DateTime;
use Symfony\Component\Uid\Uuid;

class ReserveEventDaysRequest
{
    public function __construct(
        private Uuid $eventId,
        private DateTime $startDate,
        private DateTime $endDate,
        private int $seatsNumber
    ) {}

    public function getEventId() : Uuid
    {
        return $this->eventId;
    }

    public function getStartDate() : DateTime
    {
        return $this->startDate;
    }

    public function getEndDate() : DateTime
    {
        return $this->endDate;
    }

    public function getSeatsNumber() : int
    {
        return $this->seatsNumber;
    }
}
