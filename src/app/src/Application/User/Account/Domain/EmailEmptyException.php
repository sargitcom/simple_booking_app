<?php

namespace App\Application\User\Account\Domain;

use InvalidArgumentException;
use Throwable;

class EmailEmptyException extends InvalidArgumentException
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        if ($message !== "") {
            $message .= ", ";
        }

        $message .= "User email empty exception";

        parent::__construct($message, $code, $previous);
    }
}
