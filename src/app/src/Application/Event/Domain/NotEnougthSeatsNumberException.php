<?php

namespace App\Application\Event\Domain;

use InvalidArgumentException;
use Throwable;

class NotEnougthSeatsNumberException extends InvalidArgumentException
{
    public function __construct(int $requestedSeatsNumber, int $availableSeatsNumber, string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        if ($message !== "") {
            $message .= ",";
        }

        $message .= "Requested seats number `$requestedSeatsNumber` is greater than avaialble seats number `$availableSeatsNumber` exception";
    }
}
