<?php

namespace App\Application\User\Account\Domain;

use App\Application\User\Account\Domain\Language\LanguageCode;

class Language
{
    public const LANGUAGE_CODE_LENGTH = 2;

    private string $languageCode;

    private function __construct(LanguageCode $languageCode)
    {
        $this->languageCode = $languageCode->getLanguageCode();
    }

    public static function create(LanguageCode $languageCode) : self
    {
        return new self($languageCode);
    }

    public function getLanguageCode() : string
    {
        return $this->languageCode;
    }
}
