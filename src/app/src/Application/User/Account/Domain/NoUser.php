<?php

namespace App\Application\User\Account\Domain;

class NoUser implements IsNullObject, IsLanguage
{
    public function isNullObject(): bool
    {
        return true;
    }

    public function getLanguage() : string
    {
        return LanguageFactory::getDefaultLanguage();
    }
}
