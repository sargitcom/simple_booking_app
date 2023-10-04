<?php

namespace App\Application\Event\Domain;

use InvalidArgumentException;
use Throwable;

class DomainEventNameTooLongException extends InvalidArgumentException
{
    public function __construct(string $eventName, string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        if ($message !== "") {
            $message .= ",";
        }

        $message .= "Event name $eventName too long exception";
    }
}
