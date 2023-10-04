<?php

namespace App\Application\User\Account\Application;

use App\Application\EventStore\Application\InsertDomainEvent;
use App\Application\UserAccount\Domain\UserRegisteredEvent;
use App\Application\UserAccount\Domain\UserRepository;
use Symfony\Component\Uid\Uuid;

class CreateUser
{
    private InsertDomainEvent $insertDomainEvent;
    private UserRepository $userRepository;

    public function __construct(
        InsertDomainEvent $insertDomainEvent,
        UserRepository $userRepository
    ) {
        $this->insertDomainEvent = $insertDomainEvent;
        $this->userRepository = $userRepository;
    }

    /**
     * @param CreateUserRequest $request
     * @return CreateUserResponse|UserExistsException
     * @throws UserExistsException
     */
    public function create(CreateUserRequest $request) : CreateUserResponse | UserExistsException
    {
        $isUserExists = $this->userRepository->isUserExists($request->getEmail());

        if ($isUserExists) {
            throw new UserExistsException($request->getEmail()->getEmail());
        }

        $userUuid = Uuid::v4();

        $this->insertDomainEvent->insertEvent(new UserRegisteredEvent(
            $userUuid,
            $request->getUsername(),
            $request->getEmail(),
            $request->getPassword(),
            $request->getLanguage()
        ));

        return new CreateUserResponse($userUuid->toRfc4122(), $request->getEmail()->getEmail(), $request->getUsername()->getName());
    }
}
