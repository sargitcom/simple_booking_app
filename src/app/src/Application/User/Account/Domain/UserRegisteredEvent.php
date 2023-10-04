<?php

namespace App\Application\User\Account\Domain;

use App\Application\EventStore\Domain\DomainEvent;
use App\Application\EventStore\Domain\DomainEventBody;
use Symfony\Component\Uid\Uuid;

class UserRegisteredEvent extends DomainEvent
{
    private Name $userName;
    private Email $email;
    private Password $password;
    private Language $language;

    public function __construct(Uuid $aggregateId, Name $userName, Email $email, Password $password, Language $language)
    {
        parent::__construct($aggregateId);
        $this->setUserName($userName);
        $this->setEmail($email);
        $this->setPassword($password);
        $this->setLanguage($language);
    }

    /**
     * @param Name $userName
     */
    private function setUserName(Name $userName): void
    {
        $this->userName = $userName;
    }

    /**
     * @param Email $email
     */
    private function setEmail(Email $email): void
    {
        $this->email = $email;
    }

    /**
     * @param Password $password
     */
    private function setPassword(Password $password): void
    {
        $this->password = $password;
    }

    private function setLanguage(Language $language) : void
    {
        $this->language = $language;
    }

    protected function getData(): DomainEventBody
    {
        return DomainEventBody::create([
            'username' => $this->userName->getName(),
            'email' => $this->email->getEmail(),
            'password' => $this->password->getPassword(),
            'language' => $this->language->getLanguageCode(),
        ]);
    }
}
