<?php

namespace App\Application\User\Account\Domain;

class Email
{
    public const MAX_EMAIL_LENGTH = 320;

    private string $email;

    private function __construct(string $email)
    {
        $this->assertNotEmpty($email);
        $this->assertValidLength($email);
        $this->setEmail($email);
    }

    public static function create(string $email) : self
    {
        return new self($email);
    }

    private function assertNotEmpty(string $email) : void
    {
        if ($email !== "") {
            return;
        }

        throw new EmailEmptyException();
    }

    private function assertValidLength(string $email) : void
    {
        if (mb_strlen($email) <= self::MAX_EMAIL_LENGTH) {
            return;
        }

        throw new EmailTooLongException($email);
    }

    private function setEmail(string $email) : void
    {
        $this->email = $email;
    }

    public function getEmail() : string
    {
        return $this->email;
    }

    public function __toString() : string
    {
        return $this->getEmail();
    }
}
