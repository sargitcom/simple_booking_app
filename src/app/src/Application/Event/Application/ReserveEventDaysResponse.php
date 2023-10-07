<?php

namespace App\Application\Event\Application;

use Symfony\Component\Uid\Uuid;

class ReserveEventDaysResponse
{
    public const IS_ERROR = true;
    public const IS_NO_ERROR = false;
    
    public const IS_RESERVED = true;
    public const IS_NOT_RESERVED = false;

    public function __construct(
        private Uuid $eventId,
        private bool $isReserved,
        private bool $isError,
        private string $message,
    ) {}

    public function getEventId() : string
    {
        return $this->eventId->toRfc4122();
    }

    public function isReserved() : bool
    {
        return $this->isReserved;
    }

    public function isError() : bool
    {
        return $this->isError;
    }

    public function getMessage() : string
    {
        return $this->message;
    }
}
