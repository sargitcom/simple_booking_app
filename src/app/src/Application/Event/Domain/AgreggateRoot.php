<?php

namespace App\Application\Event\Domain;

use App\Application\EventStore\Domain\DomainEvent;
use Symfony\Component\Uid\Uuid;

abstract class AggregateRoot
{
    /**
     * Uuid $id
     */
    protected Uuid $id;
    
    /**
     * int $version
     */
    protected AgreggateVersion $version;

    /** 
     * @var DomainEvent[] 
     */
    protected array $events = [];

    final public function getId() : Uuid
    {
        return $this->id;
    }

    final public function getVersion() : AgreggateVersion
    {
        return $this->version;
    }

    final public function incVersion() : self
    {
        $this->version = $this->version->inc();
        return $this;
    }
    
    /**
     *  @return DomainEvent[]
     */
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
