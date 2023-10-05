<?php

namespace App\Application\Event\Application;

use App\Application\Event\Domain\AvailableEventDay;
use App\Application\Event\Domain\AvailableEventDayRepository;

class ReserveEventDays
{
    public function __construct(private AvailableEventDayRepository $availableEventDaysRepository) {}

    public function reserveSeats()
    {
        $this->updateAvailableEventDaysSeatsNumber();
        $this->
    }

}