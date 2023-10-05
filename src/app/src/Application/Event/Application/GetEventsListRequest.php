<?php

namespace App\Application\Event\Application;

use DateTime;

class GetEventsListRequest
{
    public function __construct(private DateTime $startDate, private DateTime $endDate) {}

    public function getEventsWithSeats()
    {

    }
}