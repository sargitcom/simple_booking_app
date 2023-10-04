<?php

namespace App\Application\EventStore\Domain;

class ProjectionName
{
    public const MAX_PROJECTION_NAME_LENGTH = 1024;

    private string $projectionName;

    public function __construct(string $projectionName)
    {
        $this->assertNotEmpty($projectionName);
        $this->assertValidProjectionNameLength($projectionName);
        $this->setProjectionName($projectionName);
    }

    protected function assertNotEmpty(string $projectionName) : void
    {
        if ($projectionName !== "") {
            return;
        }

        throw new ProjectionNameEmptyException();
    }

    protected function assertValidProjectionNameLength(string $projectionName) : void
    {
        if (mb_strlen($projectionName) <= self::MAX_PROJECTION_NAME_LENGTH) {
            return;
        }

        throw new ProjectionNameToLongException($projectionName);
    }

    protected function setProjectionName(string $projectionName) : void
    {
        $this->projectionName = $projectionName;
    }

    public function getProjectionName() : string
    {
        return $this->projectionName;
    }
}
