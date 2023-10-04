<?php

namespace App\Application\EventStore\Domain;

class LastProjectionEvent
{
    protected int $id;
    protected ProjectionName $projectionName;
    protected LastEventId $lastEventId;

    public function __construct(ProjectionName $projectionName, LastEventId $lastEventId)
    {
        $this->projectionName = $projectionName;
        $this->lastEventId = $lastEventId;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return ProjectionName
     */
    public function getProjectionName(): ProjectionName
    {
        return $this->projectionName;
    }

    /**
     * @return LastEventId
     */
    public function getLastEventId(): LastEventId
    {
        return $this->lastEventId;
    }
}
