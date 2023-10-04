<?php

namespace App\Application\User\Account\Domain;

use InvalidArgumentException;
use Throwable;

class PasswordEmptyException extends InvalidArgumentException
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        if ($message !== "") {
            $message .= ", ";
        }

        $message .= "User password empty exception";

        parent::__construct($message, $code, $previous);
    }
}
