<?php

namespace App\Application\User\Account\Infrastructure\Symfony\Doctrine;

use App\Application\EventStore\Domain\ProjectionName;
use App\Application\User\Account\Domain\UserRegistrationConfirmation;
use App\Application\User\Account\Domain\UserRegistrationConfirmationRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<UserRegistrationConfirmation>
 *
 * @method UserRegistrationConfirmation|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserRegistrationConfirmation|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserRegistrationConfirmation[]    findAll()
 * @method UserRegistrationConfirmation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SymfonyUserRegistrationConfirmationRepository extends ServiceEntityRepository implements UserRegistrationConfirmationRepository
{
    public const PROJECTION_NAME = 'user_registration_confirmation';
    public const PERSISTS = true;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserRegistrationConfirmation::class);
    }

    public static function getProjectionName() : ProjectionName
    {
        return new ProjectionName(self::PROJECTION_NAME);
    }

    public function save(UserRegistrationConfirmation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function existsUserRegistrationConfirmation(Uuid $confirmationId): bool
    {
        $qb = $this->_em->createQueryBuilder();
        return $qb->select('urc.*')
            ->from(UserRegistrationConfirmation::class, 'urc')
            ->where('urc.confirmationId = :confirmationId')
            ->setParameter('confirmationId', $confirmationId->toRfc4122())
            ->getQuery()
            ->getOneOrNullResult() !== null;
    }

    /**
     * @param Uuid $userId
     * @return UserRegistrationConfirmation
     * @throws NonUniqueResultException
     * @throws UserRegistrationConfirmationNotExistsException
     */
    public function getUserRegistrationConfirmationByUserId(Uuid $userId): UserRegistrationConfirmation
    {
        $qb = $this->_em->createQueryBuilder();
        $result = $qb->select('urc.*')
                ->from(UserRegistrationConfirmation::class, 'urc')
                ->where('urc.confirmationId = :confirmationId')
                ->setParameter('urc.userId', $userId->toRfc4122())
                ->getQuery()
                ->getOneOrNullResult();

        if ($result === null) {
            throw new UserRegistrationConfirmationNotExistsException($userId);
        }

        return $result;
    }
}
