<?php

namespace App\Application\Event\Application\UpdateEventProjection;

use App\Application\Event\Domain\AgreggateVersion;
use App\Application\Event\Domain\Event;
use App\Application\Event\Domain\EventName;
use App\Application\Event\Domain\EventRepository;
use App\Application\Event\Infrastructure\Symfony\Doctrine\SymfonyDoctrineEventRepository;
use App\Application\EventStore\Domain\EventStoreRepository;
use App\Application\EventStore\Domain\LastEventId;
use App\Application\EventStore\Domain\LastProjectionEventRepository;
use Symfony\Component\Uid\Uuid;

class UpdateEventProjection
{
    public function __construct(
        private EventStoreRepository $symfonyEventStoreRepository,
        private LastProjectionEventRepository $lastProjectionEventRepository,
        private EventRepository $eventRepository,
    ) {}

    public function updateProjection() : void
    {
        $this->symfonyEventStoreRepository->listenEvents(fn(string $eventId )=> $this->tryUpdateProjection());
    }

    private function tryUpdateProjection()
    {
        $eventId = $this->lastProjectionEventRepository->getProjectionsCurrentEventId(
            SymfonyDoctrineEventRepository::getProjectionName()
        );

        $events = $this->symfonyEventStoreRepository->getEventsFrom($eventId->getEventId());


        $events->rewind();

        while ($events->valid()) {
            $event = $events->current();

            if ($event->getEventName() !== "CreateEventEvent") {
                $events->next();
                continue;    
            }

            $eventBody = json_decode($event->getEventBody(), true);

            $aggregateId = Uuid::fromString($event->getAggregateId());
            $eventName = EventName::create($eventBody["eventName"]);
            $eventVersion = AgreggateVersion::create($eventBody["version"]);
                      
            $this->tryToInsertNewEvent($aggregateId, $eventName, $eventVersion);

            $eventId = $event->getId();

            $this->lastProjectionEventRepository->updateProjectionCurrentEventId(
                SymfonyDoctrineEventRepository::getProjectionName(),
                LastEventId::create($eventId),
            );

            $events->next();
        }
    }

    private function tryToInsertNewEvent(Uuid $aggregateId, EventName $eventName, AgreggateVersion $eventVersion) : void
    {
        $entity = new Event($aggregateId, $eventName, $eventVersion);
        $this->eventRepository->save($entity, true);
    }
}
