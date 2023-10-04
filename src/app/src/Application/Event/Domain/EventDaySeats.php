<?php

namespace App\Application\Event\Domain;

class EventDaySeats
{
    private int $seats = 0;

    private function __construct(int $seats)
    {
        $this->assertSeatsNumberIsNotNegative($seats);
        $this->setSeats($seats);
    }

    public static function create(int $seats) : self
    {
        return new self($seats);
    }   

    private function assertSeatsNumberIsNotNegative(int $seats) : void
    {
        if ($seats >= 0) {
            return;
        }

        throw new SeatsNumberIsNegativeException($seats);
    }

    private function setSeats(int $seats)
    {
        $this->seats = $seats;
    }
}