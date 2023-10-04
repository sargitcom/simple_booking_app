<?php

namespace App\Application\Event\Domain;

use DateTime;
use Symfony\Component\Uid\Uuid;

class AvailableEventDay extends AggregateRoot
{
    private Uuid $id;
    private Uuid $eventId;
    private int $day;
    private int $month;
    private int $year;
    private EventDaySeats $availableSeats;
    private int $version = 1;

    private function __construct(Uuid $id, DateTime $date)
    {
        $this->assertValidDate($date);
        $this->setId($id);
        $this->setDay($date->format('d'));
        $this->setMonth($date->format('m'));
        $this->setYear($date->format('Y'));
    }

    static function create(Uuid $id, DateTime $date) : self
    {
        return new self($id, $date);
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
}
