<?php

namespace App\Application\EventStore\Domain;

use InvalidArgumentException;
use Throwable;

class DomainEventNameEmptyException extends InvalidArgumentException
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        if ($message !== "") {
            $message .= ", ";
        }

        $message .= "Domain event name empty exception";

        parent::__construct($message, $code, $previous);
    }
}
