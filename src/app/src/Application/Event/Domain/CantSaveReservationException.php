<?php

namespace App\Application\Event\Domain;

use Exception;
use Throwable;

class CantSaveReservationException extends Exception
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        if ($message !== "") {
            $message .= ",";
        }

        $message .= "Can`t save reservation exception";
    }
}
