<?php

namespace App\Application\Event\Application\UpdateEventReservedDaysProjection;

use App\Application\Event\Domain\AgreggateVersion;
use App\Application\Event\Domain\EventDaySeats;
use App\Application\Event\Domain\ReservedEventDay;
use App\Application\Event\Domain\ReservedEventDayRepository;
use App\Application\Event\Infrastructure\Symfony\Doctrine\SymfonyDoctrineReservedEventDayRepository;
use App\Application\EventStore\Domain\EventStoreRepository;
use App\Application\EventStore\Domain\LastEventId;
use App\Application\EventStore\Domain\LastProjectionEventRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;

class UpdateEventReservedDaysProjection
{
    public function __construct(
        private EventStoreRepository $symfonyEventStoreRepository,
        private LastProjectionEventRepository $lastProjectionEventRepository,
        private ReservedEventDayRepository $reservedEventDayRepository,
        private EntityManagerInterface $entityManagerInterface
    ) {}

    public function updateProjection() : void
    {
        $this->symfonyEventStoreRepository->listenEvents(
            fn(string $eventId) => $this->tryToUpdateProjection()
        );
    }

    protected function tryToUpdateProjection() : void
    {
        $this->entityManagerInterface->clear();

        $eventId = $this->lastProjectionEventRepository->getProjectionsCurrentEventId(
            SymfonyDoctrineReservedEventDayRepository::getProjectionName()
        );

        $events = $this->symfonyEventStoreRepository->getEventsFrom($eventId->getEventId());

        $events->rewind();

        while ($events->valid()) {
            $event = $events->current();

            if ($event->getEventName() !== "EventDayReservedEvent") {
                $events->next();
                continue;    
            }

            $eventBody = json_decode($event->getEventBody(), true);

            $aggregateId = Uuid::fromString($event->getAggregateId());
            $seats = EventDaySeats::create($eventBody["seats"]);
            $reservationId = Uuid::fromString($eventBody["reservationId"]);
            $eventId = Uuid::fromString($eventBody["eventId"]);
            $year = $eventBody["year"];
            $month = $eventBody["month"];
            $day = $eventBody["day"];

            $this->saveEventInProjection(
                $aggregateId,
                $seats,
                $reservationId,
                $eventId,
                $year,
                $month,
                $day,
            );
 
            $eventId = $event->getId();

            $this->lastProjectionEventRepository->updateProjectionCurrentEventId(
                SymfonyDoctrineReservedEventDayRepository::getProjectionName(),
                LastEventId::create($eventId),
            );

            $events->next();
        }
    }

    private function saveEventInProjection(
        Uuid $aggregateId,
        EventDaySeats $seats,
        Uuid $reservationId,
        Uuid $eventId,
        int $year,
        int $month,
        int $day,
    ) {
        $date = new DateTime($year . '-' . $month . '-' . $day);

        $entity = ReservedEventDay::create(
            $aggregateId,
            $reservationId,
            $eventId,
            $date,
            $seats,
            AgreggateVersion::create()
        );
        $this->reservedEventDayRepository->reserveEventDay($entity, true);
    }
}
