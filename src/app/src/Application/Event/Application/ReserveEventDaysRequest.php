<?php

namespace App\Application\Event\Application;

use Symfony\Component\Uid\Uuid;

class ReserveEventDaysRequest
{
    public function __construct(
        private Uuid $eventId,

    ) {}

}