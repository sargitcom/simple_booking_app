<?php

namespace App\Application\User\Account\Application;

use Exception;
use Throwable;

class UserExistsException extends Exception
{
    public function __construct(string $email, string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        if ($message !== "") {
            $message .= ", ";
        }

        $message .= "User $email already exists exception";

        parent::__construct($message, $code, $previous);
    }
}
