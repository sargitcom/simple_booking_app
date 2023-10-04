<?php

namespace App\Application\User\Account\Application;

class CreateUserResponse
{
    private string $userUuid;
    private string $email;
    private string $username;

    /**
     * @param string $email
     * @param string $username
     */
    public function __construct(string $userUuid, string $email, string $username)
    {
        $this->userUuid = $userUuid;
        $this->email = $email;
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getUserUuid(): string
    {
        return $this->userUuid;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }
}
