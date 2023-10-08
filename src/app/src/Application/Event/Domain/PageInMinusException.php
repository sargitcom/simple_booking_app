<?php

namespace App\Application\Event\Domain;

use InvalidArgumentException;
use Throwable;

class PageInMinusException extends InvalidArgumentException
{
    public function __construct(int $page, string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        if ($message !== "") {
            $message .= ",";
        }

        $message .= "Page $page not in plus exception";
    }
}
