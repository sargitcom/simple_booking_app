<?php

namespace App\Application\Event\Domain;

use DateTime;
use Symfony\Component\Uid\Uuid;

class AvailableEventDay extends AggregateRoot
{
    private Uuid $eventId;
    private int $day;
    private int $month;
    private int $year;
    private EventDaySeats $availableSeats;

    private function __construct(Uuid $id, Uuid $eventId, DateTime $date, EventDaySeats $availableSeats, AgreggateVersion $agreggateVersion)
    {
        $this->assertValidDate($date);
        $this->setId($id);
        $this->setEventId($eventId);
        $this->setDay($date->format('d'));
        $this->setMonth($date->format('m'));
        $this->setYear($date->format('Y'));
        $this->setAvaialbleSeats($availableSeats);
        $this->setAggregateVersion($agreggateVersion);
    }

    static function create(Uuid $id, DateTime $date, EventDaySeats $availableSeats) : self
    {
        return new self($id, $date);
    }

    public function reduceSeatsNumber(EventDaySeats $seatsNumber) : self
    {
        $availableSeatsNumber = $this->getAvailableSeats()->getSeatsNumber();
        $requestedSeatsNumber = $seatsNumber->getSeatsNumber();

        if ($this->isNotEnoughtSeats($availableSeatsNumber, $requestedSeatsNumber)) {
            throw new NotEnougthSeatsNumberException($requestedSeatsNumber, $availableSeatsNumber);
        }

        $this->raise(new UpdateAvailableSeatsNumberEvent(
            $this->getId(),
            $seatsNumber,
            $this->getVersion()
        ));

        return $this;
    }

    private function isNotEnoughtSeats(int $availableSeatsNumber, int $requestedSeatsNumber) : bool
    {
        return $availableSeatsNumber < $requestedSeatsNumber;
    }

    private function assertValidDate(DateTime $date)
    {
        $currentDate = new DateTime();

        if ($this->isEventDayYearLessthatCurrentYear($currentDate, $date)) {
            return false;
        }

        if ($this->isEventDayYearSameAsCurrentYear($currentDate, $date)) {
            if ($this->isEventMonthLessThanCurrentMonth($currentDate, $date)) {
                return false;
            }

            if ($this->isEventDayMonthSameAsCurrentMonth($currentDate, $date)) {
                if ($this->isEventDayDayLessThatCurrentDay($currentDate, $date)) {
                    return false;
                }
            }

            return true;
        }

        return true;
    }

    private function isEventDayYearLessthatCurrentYear(DateTime $currentDate, DateTime $eventDayDate) : bool
    {
        return $eventDayDate->format('Y') < $currentDate->format('Y');
    }

    private function isEventDayYearSameAsCurrentYear(DateTime $currentDate, DateTime $eventDayDate) : bool
    {
        return $eventDayDate->format('Y') === $currentDate->format('Y');
    }

    private function isEventMonthLessThanCurrentMonth(DateTime $currentDate, DateTime $eventDayDate) : bool
    {
        return $eventDayDate->format('m') < $currentDate->format('m');
    }

    private function isEventDayMonthSameAsCurrentMonth(DateTime $currentDate, DateTime $eventDayDate) : bool
    {
        return $eventDayDate->format('m') === $currentDate->format('m');
    }

    private function isEventDayDayLessThatCurrentDay(DateTime $currentDate, DateTime $eventDayDate) : bool
    {
        return $eventDayDate->format('d') < $currentDate->format('d');
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
