<?php

namespace App\Application\User\Account\Infrastructure\Symfony\Doctrine;

use App\Application\EventStore\Domain\ProjectionName;
use App\Application\User\Account\Domain\Email;
use App\Application\User\Account\Domain\User;
use App\Application\User\Account\Domain\UserRepository;
use App\Application\UserAccount\Infrastructure\Symfony\Doctrine\UserNotExistsException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SymfonyUserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface, UserRepository
{
    public const PROJECTION_NAME = 'user_projection';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public static function getProjectionName() : ProjectionName
    {
        return new ProjectionName(self::PROJECTION_NAME);
    }

    /**
     * @param Email $email
     * @return User
     * @throws NonUniqueResultException
     * @throws UserNotExistsException
     */
    public function getUserByEmail(Email $email) : User
    {
        $qb = $this->_em->createQueryBuilder();

        $result = $qb->select('u')
            ->from(User::class, 'u')
            ->where("u.email.email = 'sargitcom@gmail.com'")
            //->setParameter(':email', ':email')
            ->getQuery()
            ->getOneOrNullResult();

        if ($result === null) {
            throw new UserNotExistsException($email->getEmail());
        }

        return $result;
    }

    /**
     * @param Uuid $userId
     * @return User
     * @throws NonUniqueResultException
     * @throws UserNotExistsException
     */
    public function getUserById(Uuid $userId) : User
    {
        $qb = $this->_em->createQueryBuilder();

        $result = $qb->select('u')
                ->from(User::class, 'u')
                ->where('u.id = :userId')
                ->setParameter('userId', $userId->toRfc4122())
                ->getQuery()
                ->getOneOrNullResult();

        if ($result === null) {
            throw new UserNotExistsException($userId);
        }

        return $result;
    }

    /**
     * @param Email $email
     * @return bool
     * @throws NonUniqueResultException
     */
    public function isUserExists(Email $email) : bool
    {
        $qb = $this->_em->createQueryBuilder();

        return $qb->select('u.email')
                ->from(User::class, 'u')
                ->getQuery()
                ->getOneOrNullResult() !== null;
    }

    public function save(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->save($user, true);
    }
}
