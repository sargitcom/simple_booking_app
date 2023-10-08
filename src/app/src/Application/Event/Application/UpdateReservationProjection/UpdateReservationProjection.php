<?php

namespace App\Application\Event\Application\UpdateReservationProjection;

use App\Application\Event\Domain\AgreggateVersion;
use App\Application\Event\Domain\EventDaySeats;
use App\Application\Event\Domain\Reservation;
use App\Application\Event\Domain\ReservationRepository;
use App\Application\Event\Infrastructure\Symfony\Doctrine\SymfonyDoctrineReservationRepository;
use App\Application\EventStore\Domain\EventStoreRepository;
use App\Application\EventStore\Domain\LastEventId;
use App\Application\EventStore\Domain\LastProjectionEventRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;

class UpdateReservationProjection
{
    public function __construct(
        private EventStoreRepository $symfonyEventStoreRepository,
        private LastProjectionEventRepository $lastProjectionEventRepository,
        private ReservationRepository $reservationRepository,
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
            SymfonyDoctrineReservationRepository::getProjectionName()
        );

        $events = $this->symfonyEventStoreRepository->getEventsFrom($eventId->getEventId());

        $events->rewind();

        while ($events->valid()) {
            $event = $events->current();

            if ($event->getEventName() !== "CreateReservationEvent") {
                $events->next();
                continue;    
            }

            $eventBody = json_decode($event->getEventBody(), true);

            $aggregateId = Uuid::fromString($event->getAggregateId());

            $reservedSeats = EventDaySeats::create($eventBody["seatsNumber"]);
            $eventId = Uuid::fromString($eventBody["eventId"]);
            $startDate = new DateTime($eventBody["startDate"]);
            $endDate = new DateTime($eventBody["endDate"]);
            $agreggateVersion = AgreggateVersion::create($eventBody["version"]);

            $this->saveEventInProjection(
                $aggregateId,
                $eventId, 
                $startDate, 
                $endDate, 
                $reservedSeats, 
                $agreggateVersion
            );
 
            $eventId = $event->getId();

            $this->lastProjectionEventRepository->updateProjectionCurrentEventId(
                SymfonyDoctrineReservationRepository::getProjectionName(),
                LastEventId::create($eventId),
            );

            $events->next();
        }
    }

    private function saveEventInProjection(
        Uuid $aggregateId,
        Uuid $eventId, 
        DateTime $startDate, 
        DateTime $endDate, 
        EventDaySeats $reservedSeats, 
        AgreggateVersion $agreggateVersion
    ) {
        $entity = Reservation::create(
            $aggregateId,
            $eventId, 
            $startDate, 
            $endDate, 
            $reservedSeats, 
            $agreggateVersion
        );
        $this->reservationRepository->createReservation($entity, true);
    }
}
