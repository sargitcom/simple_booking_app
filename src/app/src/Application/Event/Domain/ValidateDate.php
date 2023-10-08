<?php

namespace App\Application\Event\Domain;

use DateTime;

class ValidateDate
{
    public static function create() : self
    {
        return new self();
    }

    public function isDateEqualOrGreaterThanCurrentDate(DateTime $date) : bool
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
}
