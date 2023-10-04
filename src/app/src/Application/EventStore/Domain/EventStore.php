<?php

namespace App\Application\EventStore\Domain;

use DateTimeImmutable;
use Symfony\Component\Uid\AbstractUid;

class EventStore
{
    protected int $id;
    protected AbstractUid $eventId;
    private AbstractUid $aggregateId;
    private int $version;
    private DomainEventName $eventName;
    private DomainEventBody $eventBody;
    private DateTimeImmutable $createdAt;

    public function __construct(
        AbstractUid $eventId,
        AbstractUid $aggregateId,
        int $version,
        DomainEventName $eventName,
        DomainEventBody $eventBody,
        DateTimeImmutable $createdAt
    ) {
        $this->eventId = $eventId;
        $this->aggregateId = $aggregateId;
        $this->version = $version;
        $this->eventName = $eventName;
        $this->eventBody = $eventBody;
        $this->createdAt = $createdAt;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getEventId(): string
    {
        return $this->eventId->toRfc4122();
    }


    /**
     * @return string
     */
    public function getAggregateId(): string
    {
        return $this->aggregateId->toRfc4122();
    }

    /**
     * @return int
     */
    public function getVersion(): int
    {
        return $this->version;
    }

    /**
     * @return string
     */
    public function getEventName(): string
    {
        return $this->eventName->getDomainEventName();
    }

    /**
     * @return string
     */
    public function getEventBody(): string
    {
        return $this->eventBody->getDomainEventBody();
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt->format('Y-m-d H:i:s');
    }
}
