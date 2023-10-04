<?php

namespace App\Application\EventStore\Domain;

use Symfony\Component\Uid\Uuid;

interface LastProjectionEventRepository
{
    public function getProjectionsCurrentEventId(ProjectionName $projectionName) : LastEventId;
    public function updateProjectionCurrentEventId(ProjectionName $projectionName, LastEventId $lastEventId) : void;
}
