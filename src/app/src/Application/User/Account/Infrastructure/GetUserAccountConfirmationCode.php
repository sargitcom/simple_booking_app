<?php

namespace App\Application\User\Account\Infrastructure;

use Symfony\Component\Uid\Uuid;

class GetUserAccountConfirmationCode
{
    public static function getCode(Uuid $userId, Uuid $confirmationId) : string
    {
        return sha1(time() . $confirmationId->toRfc4122() . $userId->toRfc4122() . rand());
    }
}
