<?php

namespace App\Application\Event\Domain;

use InvalidArgumentException;
use Throwable;

class EventNameEmptyException extends InvalidArgumentException
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        if ($message !== "") {
            $message .= ",";
        }

        $message .= "Event name is empty exception";
    }
}
