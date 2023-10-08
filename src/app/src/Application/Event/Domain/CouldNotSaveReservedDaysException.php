<?php

namespace App\Application\Event\Domain;

use Exception;
use Throwable;

class CouldNotSaveReservedDaysException extends Exception
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        if ($message !== "") {
            $message .= ",";
        }

        $message .= "Could not save reserved days exception";
    }
}
