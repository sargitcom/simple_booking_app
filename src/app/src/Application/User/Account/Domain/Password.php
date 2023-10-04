<?php

namespace App\Application\User\Account\Domain;

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class Password implements PasswordAuthenticatedUserInterface
{
    public const MAX_PASSWORD_LENGTH = 255;

    private string $password;

    private function __construct(string $password)
    {
        $this->assertNotEmpty($password);
        $this->assertValidLength($password);
        $this->setpassword($password);
    }

    public static function create(string $password) : self
    {
        return new self($password);
    }

    private function assertNotEmpty(string $password) : void
    {
        if ($password !== "") {
            return;
        }

        throw new PasswordEmptyException();
    }

    private function assertValidLength(string $password) : void
    {
        if (mb_strlen($password) <= self::MAX_PASSWORD_LENGTH) {
            return;
        }

        throw new PasswordTooLongException($password);
    }

    private function setPassword(string $password) : void
    {
        $this->password = $password;
    }

    public function getPassword() : string
    {
        return $this->password;
    }
}
