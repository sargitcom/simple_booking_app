<?php

namespace App\Application\Event\Application\GetEventsList;

use DateTime;

class GetEventsListRequest
{
    public function __construct(private DateTime $startDate, private DateTime $endDate) {}

    public function getEventsWithSeats()
    {

    }
}