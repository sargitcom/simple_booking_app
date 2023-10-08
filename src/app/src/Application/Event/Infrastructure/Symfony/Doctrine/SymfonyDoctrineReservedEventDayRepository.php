<?php

namespace App\Application\Event\Infrastructure\Symfony\Doctrine;

use App\Application\Event\Domain\AgreggateVersion;
use App\Application\Event\Domain\CouldNotSaveReservedDaysException;
use App\Application\Event\Domain\EventDaySeats;
use App\Application\Event\Domain\Page;
use App\Application\Event\Domain\ReservedEventDay;
use App\Application\Event\Domain\ReservedEventDayCollection;
use App\Application\Event\Domain\ReservedEventDayRepository;
use App\Application\EventStore\Domain\ProjectionName;
use DateInterval;
use DatePeriod;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;
use Throwable;

/**
 * @extends ServiceEntityRepository<ReservedEventDay>
 *
 * @method ReservedEventDay|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReservedEventDay|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReservedEventDay[]    findAll()
 * @method ReservedEventDay[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SymfonyDoctrineReservedEventDayRepository extends ServiceEntityRepository implements ReservedEventDayRepository
{
    public const PROJECTION_NAME = 'reserved_event_day';
    public const PERSISTS = true;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReservedEventDay::class);
    }

    public static function getProjectionName() : ProjectionName
    {
        return new ProjectionName(self::PROJECTION_NAME);
    }

    public function reserveEventDays(Uuid $eventId, Uuid $reservationId, DateTime $startDate, DateTime $endDate, int $seats) : void
    {
        $this->getEntityManager()->getConnection()->beginTransaction();

        try {
            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($startDate, $interval, $endDate->add($interval));

            foreach ($period as $date) {
                $id = Uuid::v4();
                $agreggateVersion = AgreggateVersion::create();
                $reservedSeats = EventDaySeats::create($seats);

                $entity = new ReservedEventDay(
                    $id,
                    $reservationId,
                    $eventId,
                    $date,
                    $reservedSeats,
                    $agreggateVersion
                );

                $this->reserveEventDay($entity, SymfonyDoctrineReservedEventDayRepository::PERSISTS);
            }
        } catch (Throwable $e) {
            $this->getEntityManager()->getConnection()->rollBack();
            throw new CouldNotSaveReservedDaysException();
        }

        $this->getEntityManager()->getConnection()->commit();
    }

    public function reserveEventDay(ReservedEventDay $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getReservedEventDays(Uuid $eventId, Page $page) : ReservedEventDayCollection
    {
        $collection = new ReservedEventDayCollection();

        $data = $this->getReservedDaysByEvent($eventId, $page);

        foreach ($data as $redItem) {
            $collection->append($redItem);
        }

        $collection->rewind();

        return $collection;
    }

    protected function getReservedDaysByEvent() : array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $query = $qb->select('red')
            ->from(ReservedEventDay::class, 'red')
            ->where('eventId = :eventId')
            ->setFirstResult(0)
            ->setMaxResults(10)
            ->getQuery();

        return $query->getResult();
    }
}
