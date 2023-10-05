<?php

namespace App\Application\Event\Domain;

class FullEvent
{
    public function __construct(
        private Event $event, 
        private AvailableEventDayCollection $availableEventDayCollection,
        private ReservedEventDayCollection $reservedEventDayCollection,
    ) {}

    public function getEvent() : Event
    {
        return $this->event;
    }

    public function getAvailableEventDayCollection() : AvailableEventDayCollection
    {
        return $this->availableEventDayCollection;
    }

    public function getReservedEventDayCollection() : ReservedEventDayCollection
    {
        return $this->reservedEventDayCollection;
    }
}
