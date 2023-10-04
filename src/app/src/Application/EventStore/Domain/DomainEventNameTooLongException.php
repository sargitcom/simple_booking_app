<?php

namespace App\Application\EventStore\Domain;

use InvalidArgumentException;
use Throwable;

class DomainEventNameTooLongException extends InvalidArgumentException
{
    public function __construct(string $domainEventName, string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        if ($message !== "") {
            $message .= ", ";
        }

        $message .= "Domain event name $domainEventName too long exception";

        parent::__construct($message, $code, $previous);
    }
}
