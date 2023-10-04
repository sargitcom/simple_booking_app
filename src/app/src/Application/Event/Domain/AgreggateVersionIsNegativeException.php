<?php

namespace App\Application\Event\Domain;

use InvalidArgumentException;
use Throwable;

class AgreggateVersionIsNegativeException extends InvalidArgumentException
{
    public function __construct(int $version, string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        if ($message !== "") {
            $message .= ",";
        }

        $message .= "Agreggate version `$version` is negative exception";
    }
}
