<?php

namespace App\Application\Event\Application;

use App\Application\Event\Domain\EventName;
use Symfony\Component\Uid\Uuid;

class AddEventResponse
{
    public function __construct(private Uuid $eventId, private EventName $eventName) {}

    public function getEventId() : Uuid
    {
        return $this->eventId;
    }

    public function getEventName() : EventName
    {
        return $this->eventName;
    }
}
