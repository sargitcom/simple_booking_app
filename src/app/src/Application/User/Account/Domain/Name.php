<?php

namespace App\Application\User\Account\Domain;

class Name
{
    public const MAX_NAME_LENGTH = 255;

    private string $name;

    private function __construct(string $name)
    {
        $this->assertNotEmpty($name);
        $this->assertValidLength($name);
        $this->setName($name);
    }

    public static function create(string $name) : self
    {
        return new self($name);
    }

    private function assertNotEmpty(string $name) : void
    {
        if ($name !== "") {
            return;
        }

        throw new NameEmptyException();
    }

    private function assertValidLength(string $name) : void
    {
        if (mb_strlen($name) <= self::MAX_NAME_LENGTH) {
            return;
        }

        throw new NameTooLongException($name);
    }

    private function setName(string $name) : void
    {
        $this->name = $name;
    }

    public function getName() : string
    {
        return $this->name;
    }
}
