<?php

use App\Application\Event\Domain\AvailableEventDay;
use App\Application\Event\Domain\AvailableEventDayCollection;
use App\Application\Event\Domain\AvailableEventDayRepository;
use App\Application\Event\Domain\AvailableEventDaysObsoleteVersionException;
use App\Application\Event\Domain\Event;
use App\Application\Event\Domain\FullEventCollection;
use App\Application\Event\Domain\NotEnougthSeatsNumberException;
use App\Application\EventStore\Domain\ProjectionName;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

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

    public function reserveEventDays(Uuid $uuid, DateTime $startDate, DateTime $endDate, int $seatsNumber) : void
    {
        if ($this->isRequiredDateRangeCoveredByAvailableSeats($uuid, $startDate, $endDate) === false) {
            throw new Exception("Date range do not overlapp");
        }

        $isError = false;
        $i = 0;

        do {
            try {
                $i++;

                $availableEventDays = $this->getAvailableEventDaysByRange($uuid, $startDate, $endDate);

                /**
                * @throws Exception
                */
                $this->updateAvailableEventDaysSeatsNumber($availableEventDays, $startDate, $endDate, $seatsNumber);


                $this->beginTransaction();

                /**
                * @throws Exception
                */
                $this->tryToUpdateAvailableSeats($availableEventDays);

                $this->commitTransaction();

                break;
            } catch (NotEnougthSeatsNumberException) {

            } catch (AvailableEventDaysObsoleteVersionException) {
                $this->rollbackTransaction();
            }
        } while ($i <= 5);

        // tutaj walna error jezeli taka koniecznosc throw new CantUpd();
    }

    protected function isRequiredDateRangeCoveredByAvailableSeats()
    {

    }

    protected function getAvailableEventDaysByRange(Uuid $eventId, DateTime $startDate, DateTime $endDate) : AvailableEventDayCollection
    {
        $qb = $this->_em->createQueryBuilder();
        $query = $qb->select('aed')
            ->from(AvailableEventDay::class, 'aed')
            ->where('eventId', '=', $eventId)
            ->andWhere("TO_DATE(concat(year, '-', month, '-', day), 'YYYY-MM-DD')", ">=", $startDate->format('Y-m-d'))
            ->andWhere("TO_DATE(concat(year, '-', month, '-', day), 'YYYY-MM-DD')", "<=", $endDate->format('Y-m-d'))
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

    protected function updateAvailableEventDaysSeatsNumber()
    {

    }

    /**
     * @throws AvailableEventDaysObsoleteVersionException
     */
    protected function tryToUpdateAvailableSeats(AvailableEventDayCollection $availableEventDays) : void
    {
        $availableEventDays->rewind();
        while ($availableEventDays->valid())
        {
            $eventDay = $availableEventDays->current();
            $this->tryToUpdateAvailableEventDay($eventDay);
            $availableEventDays->next();
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

        $dql = 'UPDATE available_event_day SET seats = :seats WHERE day = :day AND month = :month AND year = :year AND version = :version';
        $query = $this->getEntityManager()->createQuery($dql)
            ->setParameter(':seats', $seats)
            ->setParameter(':day', $day)
            ->setParameter(':month', $month)
            ->setParameter(':year', $year)
            ->setParameter(':version', $version);
        $rows = $query->execute();

        if ($rows === 0 ) throw new AvailableEventDaysObsoleteVersionException();
    }
}
