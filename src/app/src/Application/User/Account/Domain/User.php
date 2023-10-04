<?php

namespace App\Application\User\Account\Domain;

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;

class User implements UserInterface, PasswordAuthenticatedUserInterface, IsNullObject, IsLanguage
{
    private Uuid $id;

    private Email $email;

    private Name $name;

    private Language $language;

    /**
     * @var Password The hashed password
     */
    private Password $password;

    private array $roles = [];

    private bool $isVerified = false;

    public function __construct(Uuid $id)
    {
        $this->id = $id;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = Email::create($email);
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = Name::create($name);
    }

    /**
     * @param string $language
     */
    public function setLanguage(string $language): void
    {
        $this->language = LanguageFactory::getLanguageCode($language);
    }

    /**
     * @param array $roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    public function getId(): string
    {
        return $this->id->toRfc4122();
    }

    public function getEmail(): string
    {
        return $this->email->getEmail();
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return $this->getEmail();
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password->getPassword();
    }

    public function setPassword(string $password): self
    {
        $this->password = Password::create($password);
        return $this;
    }

    /**
     * @return string
     */
    public function getLanguage(): string
    {
        return $this->language->getLanguageCode();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name->getName();
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isNullObject(): bool
    {
        return false;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;
        return $this;
    }
}
