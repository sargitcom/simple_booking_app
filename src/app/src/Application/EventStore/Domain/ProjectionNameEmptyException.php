<?php

namespace App\Application\EventStore\Domain;

use InvalidArgumentException;
use Throwable;

class ProjectionNameEmptyException extends InvalidArgumentException
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        if ($message !== "") {
            $message .= ", ";
        }

        $message .= "Projection name empty exception";

        parent::__construct($message, $code, $previous);
    }
}
