<?php

namespace App\Application\User\Account\Infrastructure\Symfony\Doctrine;

use Exception;
use Throwable;

class UserRegistrationConfirmationNotExistsException extends Exception
{
    public function __construct(string $userId, string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        if ($message !== "") {
            $message .= ", ";
        }

        $message .= "User registration confirmation for user $userId not exists exception";

        parent::__construct($message, $code, $previous);
    }
}
