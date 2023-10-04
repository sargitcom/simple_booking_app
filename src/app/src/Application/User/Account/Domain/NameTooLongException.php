<?php

namespace App\Application\User\Account\Domain;

use InvalidArgumentException;
use Throwable;

class NameTooLongException extends InvalidArgumentException
{
    public function __construct(string $name, string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        if ($message !== "") {
            $message .= ", ";
        }

        $message .= "User name $name to long exception";

        parent::__construct($message, $code, $previous);
    }
}
