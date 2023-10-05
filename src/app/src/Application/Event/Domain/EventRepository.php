<?php

namespace App\Application\Event\Domain;

use App\Application\EventStore\Domain\ProjectionName;
use DateTime;
use Symfony\Component\Uid\Uuid;

interface EventRepository
{
    public static function getProjectionName() : ProjectionName;
    public function save(Event $entity, bool $flush = false): void;
    public function getEventsWithSeats(DateTime $startDate, DateTime $endDate) : FullEventCollection;
}
