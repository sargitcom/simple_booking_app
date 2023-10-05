<?php

namespace App\Application\Event\Domain;

use App\Application\EventStore\Domain\DomainEvent;
use App\Application\EventStore\Domain\DomainEventBody;
use Symfony\Component\Uid\Uuid;

class DecreaseAvailableSeatsNumberEvent extends DomainEvent
{
    private EventDaySeats $requestedSeatsNumber;
    private AgreggateVersion $aggregateVersion;

    public function __construct(Uuid $aggregateId, EventDaySeats $requestedSeatsNumber, AgreggateVersion $aggregateVersion)
    {
        parent::__construct($aggregateId);

        $this->requestedSeatsNumber = $requestedSeatsNumber;
        $this->aggregateVersion = $aggregateVersion;
    }

    protected function getData() : DomainEventBody
    {
        return DomainEventBody::create(
            [
                'requestedSeatsNumber' => $this->requestedSeatsNumber->getSeatsNumber(),
                'version' => $this->aggregateVersion->getVersion(),
            ]
        );
    }
}
