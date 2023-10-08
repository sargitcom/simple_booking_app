<?php

namespace App\Application\EventStore\Domain;

class LastEventId
{
    public const MIN_EVENT_ID = 1;

    private int $eventId;

    private function __construct(int $eventId)
    {
        $this->assertValidEventId($eventId);
        $this->setEventId($eventId);
    }

    public static function create(int $eventId) : self
    {
        return new self($eventId);
    }

    protected function assertValidEventId(int $eventId) : void
    {
        if ($eventId >= self::MIN_EVENT_ID) {
            return;
        }

        throw new InvalidEventIdException($eventId);
    }

    protected function setEventId(int $eventId) : void
    {
        $this->eventId = $eventId;
    }

    public function getEventId() : int
    {
        return $this->eventId;
    }
}
