<?php

namespace App\Application\User\Account\Domain;

use App\Application\EventStore\Domain\DomainEvent;
use App\Application\EventStore\Domain\DomainEventBody;
use Symfony\Component\Uid\Uuid;

class UserConfirmationEmailSentEvent extends DomainEvent
{
    private Uuid $userIdentifier;

    public function __construct(Uuid $userIdentifier)
    {
        parent::__construct($userIdentifier);
        $this->setUserIdentifier($userIdentifier);
    }

    private function setUserIdentifier(Uuid $userIdentifier): void
    {
        $this->userIdentifier = $userIdentifier;
    }

    protected function getData(): DomainEventBody
    {
        return DomainEventBody::create([
            'userId' => $this->userIdentifier->toRfc4122()
        ]);
    }
}
