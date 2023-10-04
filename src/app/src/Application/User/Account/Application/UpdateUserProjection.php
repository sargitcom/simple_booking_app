<?php

namespace App\Application\User\Account\Application;

use App\Application\EventStore\Application\InsertDomainEvent;
use App\Application\EventStore\Domain\EventStore;
use App\Application\EventStore\Domain\EventStoreRepository;
use App\Application\EventStore\Domain\LastEventId;
use App\Application\EventStore\Domain\LastProjectionEventRepository;
use App\Application\UserAccount\Domain\Email;
use App\Application\UserAccount\Domain\Language;
use App\Application\UserAccount\Domain\LanguageFactory;
use App\Application\UserAccount\Domain\Name;
use App\Application\UserAccount\Domain\Password;
use App\Application\UserAccount\Domain\User;
use App\Application\UserAccount\Domain\UserConfirmationEmailSentEvent;
use App\Application\UserAccount\Domain\UserRegistrationConfirmation;
use App\Application\UserAccount\Domain\UserRegistrationConfirmationRepository;
use App\Application\UserAccount\Domain\UserRepository;
use App\Application\UserAccount\Infrastructure\GetUserAccountConfirmationCode;
use App\Application\UserAccount\Infrastructure\Symfony\Doctrine\SymfonyUserRegistrationConfirmationRepository;
use App\Application\UserAccount\Infrastructure\Symfony\Doctrine\SymfonyUserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Uuid;

class UpdateUserProjection
{
    private InsertDomainEvent $insertDomainEvent;
    private EventStoreRepository $eventStoreRepository;
    private UserRepository $userRepository;
    private UserRegistrationConfirmationRepository $userRegistrationConfirmationRepository;
    private UserPasswordHasherInterface $userPasswordHasher;
    private LastProjectionEventRepository $lastProjectionEventRepository;

    public function __construct(
        InsertDomainEvent $insertDomainEvent,
        EventStoreRepository $eventStoreRepository,
        UserRepository $userRepository,
        UserRegistrationConfirmationRepository $userRegistrationConfirmationRepository,
        UserPasswordHasherInterface $userPasswordHasher,
        LastProjectionEventRepository $lastProjectionEventRepository
    ) {
        $this->insertDomainEvent = $insertDomainEvent;
        $this->eventStoreRepository = $eventStoreRepository;
        $this->userRepository = $userRepository;
        $this->userRegistrationConfirmationRepository = $userRegistrationConfirmationRepository;
        $this->userPasswordHasher = $userPasswordHasher;
        $this->lastProjectionEventRepository = $lastProjectionEventRepository;
    }

    public function update() : void
    {
        $this->eventStoreRepository->listenEvents([$this, 'handleNewEvents']);
    }

    public function handleNewEvents(int $eventId) : void
    {
        $events = $this->eventStoreRepository->getEventsFrom($eventId);

        $events->rewind();

        while ($events->valid()) {
            $event = $events->current();

            $eventName = $event->getEventName();

            if ($eventName !== "RegisteredUser") {
                $events->next();
                continue;
            }

            $userId = Uuid::v4();

            $this->registerUser($userId, $event);
            $this->updateUserProjectionEventTrack($eventId);
            $this->updateUserRegistrationConfirmationProjectionEventTrack($eventId);
            $this->addUserConfirmationSentEvent($userId);

            $events->next();
        }
    }

    protected function registerUser(Uuid $userId, EventStore $domainEvent) : void
    {
        $this->createUser($userId, $domainEvent);
        $this->createUserActivationCode($userId);
    }

    protected function createUser(Uuid $userId, EventStore $domainEvent) : void
    {
        $data = json_decode($domainEvent->getEventBody(), true);

        $username = Name::create($data['username']);
        $email = Email::create($data['email']);
        $password = Password::create($data['password']);
        $language = LanguageFactory::getLanguageCode($data['language']);

        $user = new User($userId, $username, $email, $language);

        $plainPassword = $password->getPassword();
        $encryptedPassword = $this->userPasswordHasher->hashPassword(
            $user,
            $plainPassword
        );
        $user->setPassword(Password::create($encryptedPassword));

        $this->userRepository->save($user);
    }

    protected function updateUserProjectionEventTrack(int $eventId) : void
    {
        $this->lastProjectionEventRepository->updateProjectionCurrentEventId(
            SymfonyUserRepository::getProjectionName(),
            LastEventId::create($eventId)
        );
    }

    protected function updateUserRegistrationConfirmationProjectionEventTrack(int $eventId) : void
    {
        $this->lastProjectionEventRepository->updateProjectionCurrentEventId(
            SymfonyUserRegistrationConfirmationRepository::getProjectionName(),
            LastEventId::create($eventId)
        );
    }

    protected function createUserActivationCode(Uuid $userId) : void
    {
        $confirmationId = Uuid::v4();
        $confirmationCode = GetUserAccountConfirmationCode::getCode($userId, $confirmationId);
        $accountActivated = UserRegistrationConfirmation::ACCOUNT_IS_NOT_ACTIVE;

        $registrationConfirmation = new UserRegistrationConfirmation(
            $confirmationId,
            $userId,
            $confirmationCode,
            $accountActivated,
        );

        $this->userRegistrationConfirmationRepository->save($registrationConfirmation);
    }

    protected function addUserConfirmationSentEvent(Uuid $userId) : void
    {
        $this->insertDomainEvent->insertEvent(new UserConfirmationEmailSentEvent($userId));
    }
}
