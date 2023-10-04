<?php

namespace App\Application\Event\Domain;

use App\Application\EventStore\Domain\DomainEvent;

abstract class AggregateRoot
{
    private int $version = 1;

    /** @var DomainEvent[] */
    private array $events = [];

    final public function getVersion() : int
    {
        return $this->version;
    }

    final public function incVersion() : void
    {
        $this->version++;
    }
    
    /** @return DomainEvent[] */
    final public function pullEvents(): array
    {
        $events = $this->events;
        $this->events = [];
 
        return $events;
    }
 
    final protected function raise(DomainEvent $event): void
    {
        $this->events[] = $event;
    }
}
