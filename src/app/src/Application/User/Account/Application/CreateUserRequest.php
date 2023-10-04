<?php

namespace App\Application\User\Account\Application;

use App\Application\UserAccount\Domain\Email;
use App\Application\UserAccount\Domain\Language;
use App\Application\UserAccount\Domain\Name;
use App\Application\UserAccount\Domain\Password;

class CreateUserRequest
{
    private Name $name;
    private Email $email;
    private Password $password;
    private Language $language;

    /**
     * @param Name $name
     * @param Email $email
     * @param Password $password
     * @param Language $language
     */
    public function __construct(Name $name, Email $email, Password $password, Language $language)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->language = $language;
    }

    /**
     * @return Name
     */
    public function getUsername(): Name
    {
        return $this->name;
    }

    /**
     * @return Email
     */
    public function getEmail(): Email
    {
        return $this->email;
    }

    /**
     * @return Password
     */
    public function getPassword(): Password
    {
        return $this->password;
    }

    /**
     * @return Language
     */
    public function getLanguage() : Language
    {
        return $this->language;
    }
}
