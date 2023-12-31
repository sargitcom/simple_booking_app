<?php

namespace App\Application\Event\Infrastructure\Symfony\Doctrine;

use App\Application\Event\Domain\AvailableEventDay;
use App\Application\Event\Domain\AvailableEventDayCollection;
use App\Application\Event\Domain\Event;
use App\Application\Event\Domain\EventRepository;
use App\Application\Event\Domain\FullEvent;
use App\Application\Event\Domain\FullEventCollection;
use App\Application\EventStore\Domain\ProjectionName;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<UserRegistrEventationConfirmation>
 *
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method UserRegistratioEventnConfirmation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SymfonyDoctrineEventRepository extends ServiceEntityRepository implements EventRepository
{
    public const PROJECTION_NAME = 'event';
    public const PERSISTS = true;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public static function getProjectionName() : ProjectionName
    {
        return new ProjectionName(self::PROJECTION_NAME);
    }

    public function save(Event $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getEventsWithSeats(DateTime $startDate, DateTime $endDate) : FullEventCollection
    {       
        $results = $this->getEventsCollection($startDate, $endDate);

        $collection = new FullEventCollection();

        /**
         * @var AvailableEventDay $eventItem
         */
        foreach ($results as $eventItem) {
            $availableSeats = $this->getAvailableSeats($eventItem->getId(), $startDate, $endDate);
            $reservedSeats = $this->getReservedSeats($eventItem->getId(), $startDate, $endDate);
        }

        return $collection;
    }

    protected function getEventsCollection(DateTime $startDate, DateTime $endDate) : array
    {
        $qb = $this->_em->createQueryBuilder();
        $query = $qb->select('e')
            ->from(Event::class, 'e')
            ->getQuery();

        return $query->getResult();
    }
}