<?php

namespace App\Application\User\Account\Domain;

use App\Application\User\Account\Domain\Language\EnglishLanguageCode;
use App\Application\User\Account\Domain\Language\PolishLanguageCode;

class LanguageFactory
{
    public static array $LANGUAGE_MAP = [
        'pl' => PolishLanguageCode::class,
        'en' => EnglishLanguageCode::class
    ];

    public static function getValidationRegex() : string
    {
        return '/[' . implode('|', array_keys(static::$LANGUAGE_MAP)) . ']{1}/';
    }

    public static function getLanguageCode(string $language) : Language
    {
        if (array_key_exists(mb_strtolower($language), static::$LANGUAGE_MAP) === false) {
            return Language::create(new static::$LANGUAGE_MAP['pl']);
        }

        return Language::create(new static::$LANGUAGE_MAP[mb_strtolower($language)]);
    }

    public static function getDefaultLanguage() : string
    {
        return 'pl';
    }
}
