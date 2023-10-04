<?php

namespace App\Application\EventStore\Domain;

use InvalidArgumentException;
use Throwable;

class ProjectionNameToLongException extends InvalidArgumentException
{
    public function __construct(string $projectionName, string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        if ($message !== "") {
            $message .= ", ";
        }

        $message .= "Projection name $projectionName to long exception";

        parent::__construct($message, $code, $previous);
    }
}
