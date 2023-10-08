<?php

namespace App\Application\Event\Domain;

use DateTime;
use Exception;
use Symfony\Component\Uid\Uuid;

class Reservation extends AggregateRoot
{
    private Uuid $eventId;
    private DateTime $startDate;
    private DateTime $endDate;
    private EventDaySeats $reservedSeats;

    private function __construct(
        Uuid $reservationId,
        Uuid $eventId,
        DateTime $startDate,
        DateTime $endDate,
        EventDaySeats $reservedSeats,
        AgreggateVersion $agreggateVersion
    ) {
        $this->assertValidStartDate($startDate);
        $this->assertValidEndDate($startDate, $endDate);

        $this->setId($reservationId);
        $this->setEventId($eventId);
        $this->setStartDate($startDate);
        $this->setEndDate($endDate);
        $this->setReservedSeats($reservedSeats);
        $this->setAggregateVersion($agreggateVersion);
    }

    static function create(
        Uuid $id, 
        Uuid $eventId, 
        DateTime $startDate, 
        DateTime $endDate, 
        EventDaySeats $reservedSeats, 
        AgreggateVersion $agreggateVersion
    ) : self {
        return new self($id, $eventId, $startDate, $endDate, $reservedSeats, $agreggateVersion);
    }

    private function assertValidStartDate(DateTime $date)
    {
        if (ValidateDate::create()->isDateEqualOrGreaterThanCurrentDate($date) === false) {
            throw new Exception("Start date not current date or greater that current date exception");
        }
    }

    private function assertValidEndDate(DateTime $startDate, DateTime $endDate) : void
    {
        $diff = $startDate->diff($endDate);
        if ($diff->days >= 0) {
            return;
        }

        throw new Exception("End date not equal or greater that start date exception");
    }

    private function setId(Uuid $id) : void
    {
        $this->id = $id;
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

    public function setStartDate(DateTime $startDate) : void
    {
        $this->startDate = $startDate;
    }

    public function getStartDate() : DateTime
    {
        return $this->startDate;
    }

    public function setEndDate(DateTime $endDate) : void
    {
        $this->endDate = $endDate;
    }

    public function getEndDate() : DateTime
    {
        return $this->endDate;
    }
}
