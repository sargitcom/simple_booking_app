<?php

namespace App\Application\EventStore\Domain;

class DomainEventName
{
    public const MAX_DOMAIN_EVENT_NAME_LENGTH = 512;

    private string $domainEventName;

    private function __construct(string $domainEventName)
    {
        $this->assertNotEmpty($domainEventName);
        $this->assertValidLength($domainEventName);
        $this->setDomainEventName($domainEventName);
    }

    public static function create(string $domainEventName) : self
    {
        return new self($domainEventName);
    }

    private function assertNotEmpty(string $domainEventName) : void
    {
        if ($domainEventName !== "") {
            return;
        }

        throw new DomainEventNameEmptyException();
    }

    private function assertValidLength(string $domainEventName) : void
    {
        if (mb_strlen($domainEventName) <= self::MAX_DOMAIN_EVENT_NAME_LENGTH) {
            return;
        }

        throw new DomainEventNameTooLongException($domainEventName);
    }

    private function setDomainEventName($domainEventName) : void
    {
        $this->domainEventName = $domainEventName;
    }

    /**
     * @return string
     */
    public function getDomainEventName(): string
    {
        return $this->domainEventName;
    }
}
