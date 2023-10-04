<?php

namespace App\Application\EventStore\Domain;

class DomainEventBody
{
    private string $domainEventBody;

    private function __construct(array $domainEventBody)
    {
        $this->setDomainEventBody($domainEventBody);
    }

    public static function create(array $domainEventBody = []) : self
    {
        return new self($domainEventBody);
    }

    private function setDomainEventBody(array $domainEventBody) : void
    {
        $this->domainEventBody = json_encode($domainEventBody);
    }

    /**
     * @return string
     */
    public function getDomainEventBody(): string
    {
        return $this->domainEventBody;
    }
}
