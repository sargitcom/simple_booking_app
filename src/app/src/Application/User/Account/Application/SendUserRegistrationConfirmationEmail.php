<?php

namespace App\Application\User\Account\Application;

use App\Application\EventStore\Application\InsertDomainEvent;
use App\Application\EventStore\Domain\EventStoreRepository;
use App\Application\EventStore\Domain\LastEventId;
use App\Application\EventStore\Domain\LastProjectionEventRepository;
use App\Application\UserAccount\Domain\GetUserRegistrationConfirmationLink;
use App\Application\UserAccount\Domain\User;
use App\Application\UserAccount\Domain\UserConfirmationEmailSentEvent;
use App\Application\UserAccount\Domain\UserRegistrationConfirmation;
use App\Application\UserAccount\Domain\UserRegistrationConfirmationRepository;
use App\Application\UserAccount\Domain\UserRepository;
use App\Application\UserAccount\Infrastructure\Symfony\Doctrine\SymfonyUserRegistrationConfirmationRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\Translation\TranslatorInterface;

class SendUserRegistrationConfirmationEmail
{
    private InsertDomainEvent $insertDomainEvent;
    private EventStoreRepository $eventStoreRepository;
    private UserRepository $userRepository;
    private UserRegistrationConfirmationRepository $userRegistrationConfirmationRepository;
    private LastProjectionEventRepository $lastProjectionEventRepository;
    private MailerInterface $mailer;
    private TranslatorInterface $translator;
    private GetUserRegistrationConfirmationLink $getUserRegistrationConfirmationLink;
    private string $sendEmail;

    public function __construct(
        InsertDomainEvent $insertDomainEvent,
        EventStoreRepository $eventStoreRepository,
        UserRepository $userRepository,
        UserRegistrationConfirmationRepository $userRegistrationConfirmationRepository,
        LastProjectionEventRepository $lastProjectionEventRepository,
        MailerInterface $mailer,
        TranslatorInterface $translator,
        GetUserRegistrationConfirmationLink $getUserRegistrationConfirmationLink,
        string $sendEmail
    ) {
        $this->insertDomainEvent = $insertDomainEvent;
        $this->eventStoreRepository = $eventStoreRepository;
        $this->userRepository = $userRepository;
        $this->userRegistrationConfirmationRepository = $userRegistrationConfirmationRepository;
        $this->lastProjectionEventRepository = $lastProjectionEventRepository;
        $this->mailer = $mailer;
        $this->translator = $translator;
        $this->getUserRegistrationConfirmationLink = $getUserRegistrationConfirmationLink;
        $this->sendEmail = $sendEmail;
    }

    public function update() : void
    {
        $this->eventStoreRepository->listenEvents([$this, 'handleNewEvents']);
    }

    /**
     * @param int $eventId
     * @return void
     * @throws TransportExceptionInterface
     */
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

            $data = json_decode($event->getEventBody(), true);

            $userId = Uuid::fromString($data['userId']);

            $this->sendUserRegistrationConfirmationEmail($userId);
            $this->updateUserProjectionEventTrack($eventId);
            $this->addUserConfirmationEmailSentEvent($userId);

            $events->next();
        }
    }

    /**
     * @param Uuid $userId
     * @return void
     * @throws TransportExceptionInterface
     */
    protected function sendUserRegistrationConfirmationEmail(Uuid $userId) : void
    {
        $user = $this->getUser($userId);
        $userRegistrationConfirmation = $this->getUserRegistrationConfirmation($userId);
        $email = $this->getEmail($user, $userRegistrationConfirmation);
        $this->mailer->send($email);
    }

    /**
     * @param User $user
     * @param UserRegistrationConfirmation $registrationConfirmation
     * @return TemplatedEmail
     *
     * think if this must/can be exported to different service
     */
    protected function getEmail(User $user, UserRegistrationConfirmation $registrationConfirmation) : TemplatedEmail
    {
        return (new TemplatedEmail())
            ->from($this->sendEmail)
            ->to(new Address($user->getEmail()))
            ->subject($this->translator->trans('email.user.registration.thank_you_message', locale: $user->getLanguage()))
            ->htmlTemplate('emails/registration_confirmation.html.twig')
            ->context([
                'activationLink' => $this->getUserRegistrationConfirmationLink->getLink(
                    $registrationConfirmation->getUserId(),
                    $registrationConfirmation->getConfirmationCode()
                ),
                'languageCode' => $user->getLanguage(),
            ])
        ;
    }

    /**
     * @param Uuid $userId
     * @return User
     */
    protected function getUser(Uuid $userId) : User
    {
        return $this->userRepository->getUserById($userId);
    }

    /**
     * @param Uuid $userId
     * @return UserRegistrationConfirmation
     */
    protected function getUserRegistrationConfirmation(Uuid $userId) : UserRegistrationConfirmation
    {
        return $this->userRegistrationConfirmationRepository->getUserRegistrationConfirmationByUserId($userId);
    }

    /**
     * @param int $eventId
     * @return void
     */
    protected function updateUserProjectionEventTrack(int $eventId) : void
    {
        $this->lastProjectionEventRepository->updateProjectionCurrentEventId(
            SymfonyUserRegistrationConfirmationRepository::getProjectionName(),
            LastEventId::create($eventId)
        );
    }

    /**
     * @param Uuid $userId
     * @return void
     */
    protected function addUserConfirmationEmailSentEvent(Uuid $userId) : void
    {
        $this->insertDomainEvent->insertEvent(new UserConfirmationEmailSentEvent($userId));
    }
}
