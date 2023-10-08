<?php

namespace App\Application\Event\Infrastructure\Symfony\Doctrine;

use App\Application\Event\Domain\Page;
use App\Application\Event\Domain\Reservation;
use App\Application\Event\Domain\ReservationCollection;
use App\Application\Event\Domain\ReservationRepository;
use App\Application\EventStore\Domain\ProjectionName;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reservation>
 *
 * @method Reservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservation[]    findAll()
 * @method Reservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SymfonyDoctrineAvailableEventDayRepository extends ServiceEntityRepository implements ReservationRepository
{
    public const PROJECTION_NAME = 'reservation';
    public const PERSISTS = true;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    public static function getProjectionName() : ProjectionName
    {
        return new ProjectionName(self::PROJECTION_NAME);
    }

    public function createReservation(Reservation $reservation, bool $flush = false) : void
    {
        $this->getEntityManager()->persist($reservation);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getReservations(Page $page) : ReservationCollection
    {
        $collection = new ReservationCollection();

        $reservations = $this->getReservationsList($page);

        foreach ($reservations as $reservation) {
            $reservations->append($reservation);
        }

        $reservations->rewind();

        return $collection;
    }
}
