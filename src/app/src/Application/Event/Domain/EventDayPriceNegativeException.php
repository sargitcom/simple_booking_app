<?php

namespace App\Application\Event\Domain;

use InvalidArgumentException;
use Throwable;

class EventDayPriceNegativeException extends InvalidArgumentException
{
    public function __construct(float $price, string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        if ($message !== "") {
            $message .= ",";
        }

        $message .= "Event price $price is negative exception";
    }
}