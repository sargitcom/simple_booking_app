<?php

namespace App\Application\Event\Domain;

class EventName
{
    public const MAX_LENGTH = 300;

    private string $eventName;

    private function __construct(string $eventName)
    {
        $this->assertEventNameNotEmpty($eventName);
        $this->assertEventNameHasValidLength($eventName);
        $this->setEventName($eventName);
    }

    public static function create(string $eventName) : self
    {
        return new self($eventName);
    }

    protected function assertEventNameNotEmpty(string $eventName)
    {
        if ($eventName !== "") {
            return;
        }

        throw new EventNameEmptyException();
    }

    protected function assertEventNameHasValidLength(string $eventName)
    {
        if (mb_strlen($eventName) <= self::MAX_LENGTH) {
            return;
        }

        throw new DomainEventNameTooLongException($eventName);
    }

    protected function setEventName(string $eventName)
    {
        $this->eventName = $eventName;
    }

    public function getEventName() : string
    {
        return $this->eventName;
    }
}
