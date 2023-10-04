<?php

namespace App\Application\User\Account\Domain;

use App\Application\EventStore\Domain\ProjectionName;
use Symfony\Component\Uid\Uuid;

interface UserRegistrationConfirmationRepository
{
    public static function getProjectionName() : ProjectionName;
    public function save(UserRegistrationConfirmation $entity, bool $flush = false): void;
    public function existsUserRegistrationConfirmation(Uuid $userId): bool;
    public function getUserRegistrationConfirmationByUserId(Uuid $userId) : UserRegistrationConfirmation;
}
