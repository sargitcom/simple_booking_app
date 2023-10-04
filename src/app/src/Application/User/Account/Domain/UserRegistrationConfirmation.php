<?php

namespace App\Application\User\Account\Domain;

use Symfony\Component\Uid\Uuid;

class UserRegistrationConfirmation
{
    public const ACCOUNT_IS_ACTIVE = true;
    public const ACCOUNT_IS_NOT_ACTIVE = false;

    private int $id;
    private Uuid $confirmationId;
    private Uuid $userId;
    private string $confirmationCode;
    private bool $accountActivated;

    /**
     * @param Uuid $confirmationId
     * @param Uuid $userId
     * @param string $confirmationCode
     * @param bool $accountActivated
     */
    public function __construct(
        Uuid $confirmationId,
        Uuid $userId,
        string $confirmationCode,
        bool $accountActivated
    ) {
        $this->confirmationId = $confirmationId;
        $this->userId = $userId;
        $this->confirmationCode = $confirmationCode;
        $this->accountActivated = $accountActivated;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Uuid
     */
    public function getConfirmationId(): Uuid
    {
        return $this->confirmationId;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId->toRfc4122();
    }

    /**
     * @return string
     */
    public function getConfirmationCode(): string
    {
        return $this->confirmationCode;
    }

    /**
     * @return bool
     */
    public function isAccountActivated(): bool
    {
        return $this->accountActivated;
    }
}
