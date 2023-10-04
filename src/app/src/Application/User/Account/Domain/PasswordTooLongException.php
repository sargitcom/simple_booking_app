<?php

namespace App\Application\User\Account\Domain;

use InvalidArgumentException;
use Throwable;

class PasswordTooLongException extends InvalidArgumentException
{
    public function __construct(string $password, string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        if ($message !== "") {
            $message .= ", ";
        }

        $message .= "User name $password to long exception";

        parent::__construct($message, $code, $previous);
    }
}
