<?php

namespace App\Application\User\Account\Domain;

use App\Application\EventStore\Domain\ProjectionName;
use Symfony\Component\Uid\Uuid;

interface UserRepository
{
    public const PERSIST = true;

    public static function getProjectionName() : ProjectionName;
    public function save(User $entity, bool $flush = false): void;
    public function isUserExists(Email $email) : bool;
    public function getUserById(Uuid $userId) : User;
    public function getUserByEmail(Email $email) : User;
}
