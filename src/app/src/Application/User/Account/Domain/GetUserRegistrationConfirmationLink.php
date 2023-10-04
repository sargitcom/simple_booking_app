<?php

namespace App\Application\User\Account\Domain;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class GetUserRegistrationConfirmationLink
{
    private UrlGeneratorInterface $router;

    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }

    public function getLink(string $userId, string $activationCode) : string
    {
        return $this->router->generate('user-account-activation', [
            'userId' => $userId,
            'activationCode' => $activationCode
        ]);
    }
}
