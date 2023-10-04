<?php

namespace App\Application\User\Account\Domain\Language;

abstract class LanguageCode
{
    abstract public function getLanguageCode() : string;
}
