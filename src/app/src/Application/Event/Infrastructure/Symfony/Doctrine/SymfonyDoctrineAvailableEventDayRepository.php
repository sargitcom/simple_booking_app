<?php

namespace App\Application\Event\Infrastructure\Symfony\Doctrine;

use App\Application\Event\Domain\AvailableEventDay;
use App\Application\Event\Domain\AvailableEventDayCollection;
use App\Application\Event\Domain\AvailableEventDayRepository;
use App\Application\Event\Domain\AvailableEventDaysObsoleteVersionException;
use App\Application\Event\Domain\CouldNotReserveSeatsException;
use App\Application\Event\Domain\NotEnoughtSeatsAvailableException;
use App\Application\Event\Infrastructure\Symfony\Doctrine\AvailableEventDaysObsoleteVersionException as DoctrineAvailableEventDaysObsoleteVersionException;
use App\Application\EventStore\Domain\ProjectionName;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Component\Uid\Uuid;
use Throwable;

/**
 * @extends ServiceEntityRepository<AvailableEventDay>
 *
 * @method AvailableEventDay|null find($id, $lockMode = null, $lockVersion = null)
 * @method AvailableEventDay|null findOneBy(array $criteria, array $orderBy = null)
 * @method AvailableEventDay[]    findAll()
 * @method AvailableEventDay[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SymfonyDoctrineAvailableEventDayRepository extends ServiceEntityRepository implements AvailableEventDayRepository
{
    public const PROJECTION_NAME = 'available_event_days';
    public const PERSISTS = true;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AvailableEventDay::class);
    }

    public static function getProjectionName() : ProjectionName
    {
        return new ProjectionName(self::PROJECTION_NAME);
    }

    public function save(AvailableEventDay $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getReservedDays(Uuid $uuid, DateTime $startDate, DateTime $endDate) : AvailableEventDayCollection
    {
        $collection = new AvailableEventDayCollection();

        return $collection;
    }

    public function reserveEventDays(Uuid $eventId, DateTime $startDate, DateTime $endDate, int $seatsNumber) : void
    {
        $shouldRetry = false;
        $retriesNumber = 5; // this can set using DI as a config param - for simplicity left as hardcoded value

        do {
            --$retriesNumber;

            try {
                $this->tryToReserveEventDays($eventId, $startDate, $endDate, $seatsNumber);
            } catch (DoctrineAvailableEventDaysObsoleteVersionException) {
                $shouldRetry = true;
            } catch (Exception $e) {
                throw $e;
            }
        } while ($shouldRetry && $retriesNumber > 0);

        if ($shouldRetry && $retriesNumber === 0) throw new CouldNotReserveSeatsException();
    }

    protected function tryToReserveEventDays(Uuid $uuid, DateTime $startDate, DateTime $endDate, int $seatsNumber) : void
    {
        if ($this->areAvailableSeats($uuid, $startDate, $endDate, $seatsNumber) === false) {
            throw new NotEnoughtSeatsAvailableException("There`s not enought seats available");
        }

        $availableEventDays = $this->getAvailableEventDaysSeatsByRange($uuid, $startDate, $endDate);

        if ($this->areEnoughtDaysInCollection($availableEventDays, $startDate, $endDate) === false) {
            throw new NotEnoughtSeatsAvailableException("There`s not enought seats available");
        }

        $availableEventDays->reduceSeatsNumber($seatsNumber);

        $this->_em->getConnection()->beginTransaction();

        try {
            $this->updateAvailableEventDaysSeatsNumber($availableEventDays);
        } catch(Throwable $ex) {
            $this->_em->getConnection()->rollBack();
            throw new Exception("not enought seats");
        }

        $this->_em->getConnection()->commit();
    }

    protected function areAvailableSeats(
        Uuid $eventId, 
        DateTime $startDate, 
        DateTime $endDate, 
        int $seatsNumber
    ) : bool {
        $interval = $startDate->diff($endDate);
        $daysToReserve = $interval->days > 0 ? $interval->days + 1 : 1;

        $sql = <<<SQL
SELECT 
count(aed.event_id) FROM available_event_day as aed 
WHERE aed.event_id = :eventId AND
to_date(concat(aed.year, '-', aed.month, '-', aed.day), 'YYYY-MM-DD') >= :startDate AND
to_date(concat(aed.year, '-', aed.month, '-', aed.day), 'YYYY-MM-DD') <= :endDate AND
aed.seats >= $seatsNumber
SQL;

        $conn = $this->getEntityManager()->getConnection();

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':eventId', $eventId->toRfc4122());
        $stmt->bindValue(':startDate', $startDate->format('Y-m-d'));
        $stmt->bindValue(':endDate', $endDate->format('Y-m-d'));

        $results = $stmt->executeQuery();

        return $results->fetchFirstColumn()[0] === $daysToReserve;
    }

    protected function getAvailableEventDaysSeatsByRange(
        Uuid $eventId, 
        DateTime $startDate, 
        DateTime $endDate
    ) : AvailableEventDayCollection {
        $qb = $this->_em->createQueryBuilder();
        $query = $qb->select('aed')
            ->from(AvailableEventDay::class, 'aed')
            ->where('aed.eventId = :eventId')
            ->andWhere("to_date(concat(aed.year, '-', aed.month, '-', aed.day), 'YYYY-MM-DD') >= :startDate")
            ->andWhere("to_date(concat(aed.year, '-', aed.month, '-', aed.day), 'YYYY-MM-DD') <= :endDate")
            ->orderBy("to_date(concat(aed.year, '-', aed.month, '-', aed.day), 'YYYY-MM-DD')", "ASC")
            ->setParameter(':eventId', $eventId->toRfc4122())
            ->setParameter(':startDate', $startDate->format('Y-m-d'))
            ->setParameter(':endDate', $endDate->format('Y-m-d'))
            ->getQuery();

        /**
         * @var AvailableEventDay[] $results
         */
        $results = $query->getResult();

        $collection = new AvailableEventDayCollection();

        foreach ($results as $aedItem) {
            $collection->append($aedItem);
        }

        return $collection;
    }

    protected function areEnoughtDaysInCollection(AvailableEventDayCollection $collection, DateTime $startDate, DateTime $endDate) : bool
    {
        $interval = $startDate->diff($endDate);
        $daysToReserve = $interval->days > 0 ? $interval->days + 1 : 1;

        return $collection->count() === $daysToReserve;
    }

    protected function updateAvailableEventDaysSeatsNumber(
        AvailableEventDayCollection $availableEventDayCollection
    ) {
        $availableEventDayCollection->rewind();
        while ($availableEventDayCollection->valid()) {
            $seat = $availableEventDayCollection->current();
            $this->tryToUpdateAvailableEventDay($seat);
            $availableEventDayCollection->next();
        }
    }

    /**
     * @throws AvailableEventDaysObsoleteVersionException
     */
    protected function tryToUpdateAvailableEventDay(AvailableEventDay $availableEventDay) : void
    {
        $seats = $availableEventDay->getAvailableSeats()->getSeatsNumber();
        $day = $availableEventDay->getDay();
        $month = $availableEventDay->getMonth();
        $year = $availableEventDay->getYear();
        $version = $availableEventDay->getVersion();

        $class = AvailableEventDay::class;

        $dql = <<<DQL
UPDATE $class as aed 
SET aed.availableSeats.seats = :seats, aed.version.version = aed.version.version + 1
WHERE aed.day = :day AND aed.month = :month AND aed.year = :year AND aed.version.version = :version
DQL;
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter(':seats', $seats)
            ->setParameter(':day', $day)
            ->setParameter(':month', $month)
            ->setParameter(':year', $year)
            ->setParameter(':version', $version->getVersion());
        $rows = $query->execute();

        if ($rows === 0 ) throw new DoctrineAvailableEventDaysObsoleteVersionException();
    }
}
