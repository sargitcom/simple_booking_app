<?php

namespace App\Application\User\Account\Infrastructure\Symfony\Doctrine;

use Exception;
use Throwable;

class UserNotExistsException extends Exception
{
    public function __construct(string $userId, string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        if ($message !== "") {
            $message .= ", ";
        }

        $message .= "User $userId not exists exception";

        parent::__construct($message, $code, $previous);
    }
}
