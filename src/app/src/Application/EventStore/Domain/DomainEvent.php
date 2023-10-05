<?php

namespace App\Application\EventStore\Domain;

use App\Application\Event\Domain\AgreggateVersion;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;

abstract class DomainEvent
{
    protected AgreggateVersion $version;

    protected Uuid $aggregateId;

    public function __construct(Uuid $aggregateId)
    {
        $this->setAggregateId($aggregateId);
    }

    protected function setAggregateId(Uuid $aggregateId) : void
    {
        $this->aggregateId = $aggregateId;
    }

    protected function getEventName() : DomainEventName
    {
        $class = explode('\\', get_class($this));
        return DomainEventName::create(end($class));
    }

    protected function getVersion() : int
    {
        return $this->version->getVersion();
    }

    abstract protected function getData() : DomainEventBody;

    public function toEventStore(Uuid $eventId) : EventStore
    {
        return new EventStore(
            $eventId,
            $this->aggregateId,
            $this->version->getVersion(),
            $this->getEventName(),
            $this->getData(),
            new DateTimeImmutable(),
        );
    }
}
