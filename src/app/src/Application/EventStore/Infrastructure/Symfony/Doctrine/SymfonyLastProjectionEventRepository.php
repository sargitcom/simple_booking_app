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
use Throwable;

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
        $sql = <<<SQL
INSERT INTO last_event_store_projection_event (id, event_id, projection_name)
VALUES(nextval('last_projection_event_store_seq'), :eventId, :projectionName)
ON CONFLICT (projection_name) 
DO 
   UPDATE SET event_id = :eventId2;
SQL;

        $conn = $this->getEntityManager()->getConnection();

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':eventId', $lastEventId->getEventId());
        $stmt->bindValue(':eventId2', $lastEventId->getEventId());
        $stmt->bindValue(':projectionName', $projectionName->getProjectionName());

        $stmt->executeQuery();
    }
}
