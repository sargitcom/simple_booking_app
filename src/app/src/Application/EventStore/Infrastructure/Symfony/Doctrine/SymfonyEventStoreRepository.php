<?php

namespace App\Application\EventStore\Infrastructure\Symfony\Doctrine;

use App\Application\EventStore\Domain\EventStore;
use App\Application\EventStore\Domain\EventStoreCollection;
use App\Application\EventStore\Domain\EventStoreRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use PDO;
use Symfony\Component\Uid\Uuid;

/**
 * @extends SymfonyEventStoreRepository<EventStore>
 *
 * @method EventStore|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventStore|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventStore[]    findAll()
 * @method EventStore[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SymfonyEventStoreRepository extends ServiceEntityRepository implements EventStoreRepository
{
    public const EVENT_STORE_NOTIFICATION_CHANNEL = 'event_store_new_event';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventStore::class);
    }

    public function getNextIdentifier(): Uuid
    {
        return Uuid::v4();
    }

    public function save(EventStore $eventStore): void
    {
        $this->_em->persist($eventStore);
        $this->_em->flush();
    }

    public function getEventsFrom(int $eventId): EventStoreCollection
    {
        $qb = $this->_em->createQueryBuilder();
        $query = $qb->select('es')
            ->from(EventStore::class, 'es')
            ->where('es.id >= :eventId')
            ->orderBy('es.id', 'ASC')
            ->setParameter(":eventId", $eventId)
            ->getQuery();

        $results = $query->getResult();

        $collection = new EventStoreCollection();

        foreach ($results as $eventStore) {
            $collection->append($eventStore);
        }

        return $collection;
    }

    public function listenEvents(callable $callback) : void
    {
        $channel = self::EVENT_STORE_NOTIFICATION_CHANNEL;

        $db = $this->_em->getConnection()->getNativeConnection();

        $sql = <<<SQL
listen {$channel}
SQL;

        $db->exec($sql);

        while (true) {
            while ($message = $db->pgsqlGetNotify(PDO::FETCH_ASSOC, 30000)) {

                $data = json_decode($message['payload'], true);

                $callback($data['eventId']);
            }
        }
    }
}
