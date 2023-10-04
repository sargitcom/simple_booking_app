<?php

namespace App\Application\Event\Domain;

use InvalidArgumentException;
use Throwable;

class SeatsNumberIsNegativeException extends InvalidArgumentException
{
    public function __construct(int $seatsNumber, string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        if ($message !== "") {
            $message .= ",";
        }

        $message .= "Seats number `$seatsNumber` is negative exception";
    }
}
