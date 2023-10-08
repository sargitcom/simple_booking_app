<?php

namespace App\Application\Event\Domain;

use DateTime;
use Symfony\Component\Uid\Uuid;

interface AvailableEventDayRepository
{
    public function save(AvailableEventDay $eventDay, bool $flush = false) : void;

    public function addAvailableDays(Uuid $eventId, DateTime $startDate, DateTime $endDate, int $seatsNumber);

    public function reserveEventDays(Uuid $uuid, DateTime $startDate, DateTime $endDate, int $seatsNumber) : void;

    public function getReservedDays(Uuid $uuid, DateTime $startDate, DateTime $endDate) : AvailableEventDayCollection;
}
