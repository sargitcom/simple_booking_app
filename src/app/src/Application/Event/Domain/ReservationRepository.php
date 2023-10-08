<?php

namespace App\Application\Event\Domain;

use App\Application\EventStore\Domain\ProjectionName;

interface ReservationRepository
{
    public static function getProjectionName() : ProjectionName;
    public function createReservation(Reservation $reservation) : void;
    public function getReservations(Page $page) : ReservationCollection;
}
