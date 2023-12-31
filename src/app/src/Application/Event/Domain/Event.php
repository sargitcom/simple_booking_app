<?php

namespace App\Application\Event\Domain;

use Symfony\Component\Uid\Uuid;

class Event extends AggregateRoot
{
    private EventName $eventName;

    public function __construct(Uuid $id, EventName $eventName, AgreggateVersion $agreggateVersion)
    {
        $this->id = $id;
        $this->eventName = $eventName;
        $this->version = $agreggateVersion;
    }

    public function updateName(EventName $eventName) : void
    {
        if ($this->eventName->getEventName() === "") {
            throw new EventDoesNotExistsException($this->id);
        }

        $this->setEventName($eventName);
        //$this->raise(new UpdateEventEven($this->getId(), $this->getEventName(), $this->incVersion()->getVersion()));
    }

    public function create() : void
    {
        $this->raise(new CreateEventEvent($this->getId(), $this->getEventName()));
    }

    private function setEventName(EventName $eventName) : self
    {
        $this->eventName = $eventName;
        return $this;
    }

    public function getEventName() : EventName
    {
        return $this->eventName;
    }
}
