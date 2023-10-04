<?php

namespace App\Application\User\Account\Domain;

use InvalidArgumentException;
use Throwable;

class EmailTooLongException extends InvalidArgumentException
{
    public function __construct(string $email, string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        if ($message !== "") {
            $message .= ", ";
        }

        $message .= "User email $email to long exception";

        parent::__construct($message, $code, $previous);
    }
}
