<?php

namespace App\Application\Event\Domain;

use Exception;
use Throwable;

class AvailableEventDaysObsoleteVersionException extends Exception
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        if ($message !== "") {
            $message .= ",";
        }

        $message .= "Available event days obsolete version exception";
    }
}
