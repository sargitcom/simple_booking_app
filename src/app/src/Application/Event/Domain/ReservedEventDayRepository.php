<?php

namespace App\Application\Event\Domain;

use App\Application\EventStore\Domain\ProjectionName;
use DateTime;
use Symfony\Component\Uid\Uuid;

interface ReservedEventDayRepository
{
    public static function getProjectionName() : ProjectionName;
    public function reserveEventDays(Uuid $eventId, DateTime $startDate, DateTime $endDate, int $seats) : void;
    public function reserveEventDay(ReservedEventDay $entity, bool $flush = false): void;
    public function getReservedEventDays(Uuid $eventId, Page $page) : ReservedEventDayCollection;
}
