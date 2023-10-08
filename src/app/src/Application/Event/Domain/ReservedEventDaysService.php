<?php

namespace App\Application\Event\Domain;

use App\Application\EventStore\Application\InsertDomainEvent;
use DateInterval;
use DatePeriod;
use DateTime;
use Symfony\Component\Uid\Uuid;
use Throwable;

class ReservedEventDayService
{
    public function __construct(private InsertDomainEvent $insertDomainEvent) {}

    public function reserveEventDays(
        Uuid $eventId,
        DateTime $startDate,
        DateTime $endDate,
        int $seatsNumber
    ) : void {
        try {
            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($startDate, $interval, $endDate);

            foreach ($period as $date) {
                $this->reserveEventDay($eventId, $date, $seatsNumber);
            }
        } catch (Throwable) {
            throw new CouldNotReserveSeatsException();
        }
    }

    protected function reserveEventDay(Uuid $eventId, DateTime $date, int $seatsNumber)
    {
        $aggregateId = Uuid::v4();
        $reservedSeats = EventDaySeats::create($seatsNumber);

        $event = new EventDayReservedEvent(
            $aggregateId,
            $reservedSeats,
            $eventId,
            $date
        );

        $this->insertDomainEvent->insertEvent($event);
    }
}
