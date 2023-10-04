<?php

namespace App\Application\User\Account\Application;

use App\Application\UserAccount\Domain\Email;
use App\Application\UserAccount\Domain\UserRepository;
use Psr\Cache\InvalidArgumentException;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class IsUserExists
{
    private UserRepository $userRepository;
    private CacheInterface $registrationCache;

    public function __construct(UserRepository $userRepository, CacheInterface $registrationCache)
    {
        $this->userRepository = $userRepository;
        $this->registrationCache = $registrationCache;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function isUserExists(Email $email) : bool
    {
        $isRegistered = $this->registrationCache->get($email->getEmail(), function (ItemInterface $item) {
            return $item->get();
        });

        if ($isRegistered !== null) {
            return true;
        }

        return $this->userRepository->isUserExists($email);
    }
}
