<?php

namespace App\Application\Event\Domain;

use DateTime;
use Symfony\Component\Uid\Uuid;

class AvailableEventDay extends AggregateRoot
{
    private Uuid $eventId;
    private EventDaySeats $availableSeats;
    private int $day;
    private int $month;
    private int $year;

    private function __construct(
        Uuid $id, 
        Uuid $eventId,
        DateTime $date, 
        EventDaySeats $availableSeats, 
        AgreggateVersion $agreggateVersion
    ) {
        $this->assertValidDate($date);
        $this->setId($id);
        $this->setEventId($eventId);
        $this->setDay($date->format('d'));
        $this->setMonth($date->format('m'));
        $this->setYear($date->format('Y'));
        $this->setAvaialbleSeats($availableSeats);
        $this->setAggregateVersion($agreggateVersion);
    }

    static function create(
        Uuid $id, 
        Uuid $eventId, 
        DateTime $date, 
        EventDaySeats $availableSeats, 
        AgreggateVersion $agreggateVersion
    ) : self {
        return new self($id, $eventId, $date, $availableSeats, $agreggateVersion);
    }

    public function increaseSeatsNumber(EventDaySeats $seatsNumber) : self
    {
        $this->raise(new IncreaseAvailableSeatsNumberEvent(
            $this->getId(),
            $seatsNumber->add($seatsNumber),
            $this->getVersion()->inc()
        ));

        return $this;
    }

    public function reduceSeatsNumber(EventDaySeats $seatsNumber) : self
    {
        $availableSeatsNumber = $this->getAvailableSeats()->getSeatsNumber();
        $requestedSeatsNumber = $seatsNumber->getSeatsNumber();

        if ($this->isNotEnoughtSeats($availableSeatsNumber, $requestedSeatsNumber)) {
            throw new NotEnougthSeatsNumberException($requestedSeatsNumber, $availableSeatsNumber);
        }

        $this->raise(new DecreaseAvailableSeatsNumberEvent(
            $this->getId(),
            $seatsNumber,
            $this->getVersion()->inc()
        ));

        return $this;
    }

    private function isNotEnoughtSeats(int $availableSeatsNumber, int $requestedSeatsNumber) : bool
    {
        return $availableSeatsNumber < $requestedSeatsNumber;
    }

    private function assertValidDate(DateTime $date)
    {
        return ValidateDate::create()->isDateEqualOrGreaterThanCurrentDate($date);
    }

    private function setId($id) : void
    {
        $this->id = $id;
    }

    private function setEventId($eventId) : void
    {
        $this->eventId = $eventId;
    }

    public function getEventId() : Uuid
    {
        return $this->eventId;
    }

    private function setDay(int $day) : void
    {
        $this->day = $day;
    }

    private function setMonth(int $month) : void
    {
        $this->month = $month;
    }

    private function setYear(int $year) : void
    {
        $this->year = $year;
    }  

    public function getDay() : int
    {
        return $this->day;
    }

    public function getMonth() : int
    {
        return $this->month;
    }

    public function getYear() : int
    {
        return $this->year;
    }

    private function setAvaialbleSeats(EventDaySeats $availableSeats)
    {
        $this->availableSeats = $availableSeats;
    }

    public function getAvailableSeats() : EventDaySeats
    {
        return $this->availableSeats;
    }

    private function setAggregateVersion(AgreggateVersion $agreggateVersion) : void
    {
        $this->version = $agreggateVersion;
    }
}
