<?php

namespace App\Application\EventStore\Domain;

use InvalidArgumentException;
use Throwable;

class InvalidEventIdException extends InvalidArgumentException
{
    public function __construct(int $eventId, string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        if ($message !== "") {
            $message .= ", ";
        }

        $minimumLastEventId = LastEventId::MIN_EVENT_ID;

        $message .= "Event ID $eventId not valid exception. Minimum event id is $minimumLastEventId";

        parent::__construct($message, $code, $previous);
    }
}
