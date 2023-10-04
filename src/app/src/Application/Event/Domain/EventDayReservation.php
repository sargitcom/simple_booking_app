<?php

namespace App\Application\Event\Domain;

use Symfony\Component\Uid\Uuid;

class EventDayReservation
{
    private Uuid $eventId;
    private EventDayCollection $reservedDays;

    public function __construct(Uuid $eventId, EventDayCollection $reservedDays)
    {
        
    }
}