<?php

namespace App\Application\EventStore\Infrastructure\Symfony\Doctrine;

use App\Application\EventStore\Domain\LastEventId;
use App\Application\EventStore\Domain\LastProjectionEvent;
use App\Application\EventStore\Domain\LastProjectionEventRepository;
use App\Application\EventStore\Domain\ProjectionName;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends SymfonyEventStoreRepository<LastProjectionEvent>
 *
 * @method LastProjectionEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method LastProjectionEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method LastProjectionEvent[]    findAll()
 * @method LastProjectionEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SymfonyLastProjectionEventRepository extends ServiceEntityRepository implements LastProjectionEventRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LastProjectionEvent::class);
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function getProjectionsCurrentEventId(ProjectionName $projectionName): LastEventId
    {
        $qb = $this->_em->createQueryBuilder();
        $query = $qb->select('lpe')
            ->from(LastProjectionEvent::class, 'lpe')
            ->where('lpe.projectionName.projectionName = :projectionName')
            ->setParameter(":projectionName", $projectionName->getProjectionName())
            ->getQuery();

        $result = $query->getOneOrNullResult();

        return $result !== null ? $result->getLastEventId() : LastEventId::create(1);
    }

    public function updateProjectionCurrentEventId(ProjectionName $projectionName, LastEventId $lastEventId): void
    {
        if ($this->getProjectionsCurrentEventId($projectionName)->getEventId() === 1) {
            $entity = new LastProjectionEvent($projectionName, $lastEventId);
            $this->getEntityManager()->persist($entity, true);
            return;
        }

        $qb = $this->_em->createQueryBuilder();
        $query = $qb->update(LastProjectionEvent::class, 'lpe')
            ->where('lpe.projectionName.projectionName = :projectionName')
            ->set('lpe.lastEventId.eventId', $lastEventId->getEventId())
            ->setParameter(":projectionName", $projectionName->getProjectionName())
            ->getQuery();
        $query->execute();
    }
}
