<?php

namespace App\Application\Event\Domain;

use DateTime;
use Symfony\Component\Uid\Uuid;

class ReservedEventDay extends AggregateRoot
{
    private Uuid $eventId;
    private Uuid $reservationId;
    private int $day;
    private int $month;
    private int $year;
    private EventDaySeats $reservedSeats;

    private function __construct(
        Uuid $id,
        Uuid $reservationId,
        Uuid $eventId,
        DateTime $date,
        EventDaySeats $reservedSeats,
        AgreggateVersion $agreggateVersion
    ) {
        $this->assertValidDate($date);
        $this->setId($id);
        $this->setReservationId($reservationId);
        $this->setEventId($eventId);
        $this->setDay($date->format('d'));
        $this->setMonth($date->format('m'));
        $this->setYear($date->format('Y'));
        $this->setReservedSeats($reservedSeats);
        $this->setAggregateVersion($agreggateVersion);
    }

    static function create(
        Uuid $id, 
        Uuid $reservationId,
        Uuid $eventId, 
        DateTime $date, 
        EventDaySeats $reservedSeats, 
        AgreggateVersion $agreggateVersion
    ) : self {
        return new self($id, $reservationId, $eventId, $date, $reservedSeats, $agreggateVersion);
    }

    private function assertValidDate(DateTime $date)
    {
        return ValidateDate::create()->isDateEqualOrGreaterThanCurrentDate($date);
    }

    private function setId(Uuid $id) : void
    {
        $this->id = $id;
    }

    private function setReservationId(Uuid $eventId) : void
    {
        $this->eventId = $eventId;
    }

    public function getReservationId() : Uuid
    {
        return $this->eventId;
    }

    private function setEventId(Uuid $eventId) : void
    {
        $this->eventId = $eventId;
    }

    public function getEventId() : Uuid
    {
        return $this->eventId;
    }

    private function setReservedSeats(EventDaySeats $reservedSeats) : void
    {
        $this->reservedSeats = $reservedSeats;
    }

    public function getReservedSeats() : EventDaySeats
    {
        return $this->reservedSeats;
    }

    private function setAggregateVersion(AgreggateVersion $agreggateVersion) : void
    {
        $this->version = $agreggateVersion;
    }

    private function getAggregateVersion() : AgreggateVersion
    {
        return $this->version;
    }

    private function setDay(int $day) : void
    {
        $this->day = $day;
    }

    public function getDay() : int
    {
        return $this->day;
    }

    private function setMonth(int $month) : void
    {
        $this->month = $month;
    }

    public function getMonth() : int
    {
        return $this->month;
    }

    private function setYear(int $year) : void
    {
        $this->year = $year;
    }  

    public function getYear() : int
    {
        return $this->year;
    }
}
