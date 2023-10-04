<?php

namespace App\Application\EventStore\Domain;

use Symfony\Component\Uid\Uuid;

interface EventStoreRepository
{
    public function getNextIdentifier() : Uuid;
    public function save(EventStore $eventStore) : void;
    public function getEventsFrom(int $eventId) : EventStoreCollection;
    public function listenEvents(callable $callback) : void;
}
