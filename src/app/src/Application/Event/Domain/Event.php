<?php

namespace App\Application\Event\Domain;

use Symfony\Component\Uid\Uuid;

class Event extends AggregateRoot
{
    private Uuid $id;
    private EventName $eventName;

    public function __construct(Uuid $id, EventName $eventName)
    {
        $this->id = $id;
        $this->eventName = $eventName;
    }

    public function updateName(EventName $eventName) : void
    {
        if ($this->eventName->getEventName() === "") {
            throw new EventDoesNotExistsException($this->id);
        }

        $this->eventName = $eventName;

        $this->raise(new UpdateEventEvent($this->id, $eventName));
    }

    public function save() : void
    {
        $this->raise(new CreateEventEvent($this->id, $this->eventName));
    }

    private function setEventName(EventName $eventName) : self
    {
        $this->eventName = $eventName;
        return $this;
    }

    
}
