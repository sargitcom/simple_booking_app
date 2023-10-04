<?php

namespace App\Application\Event\Domain;

use Exception;
use Symfony\Component\Uid\Uuid;
use Throwable;

class EventDoesNotExistsException extends Exception
{
    public function __construct(Uuid $id, string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        if ($message !== "") {
            $message .= ",";
        }

        $uuid = $id->toRfc4122();

        $message .= "Event $uuid does not exists exception";
    }
}
