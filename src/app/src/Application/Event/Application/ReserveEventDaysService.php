<?php

namespace App\Application\Event\Application;

use App\Application\Event\Domain\AvailableEventDayRepository;
use App\Application\Event\Domain\CouldNotReserveSeatsException;
use App\Application\Event\Domain\CouldNotSaveReservedDaysException;
use App\Application\Event\Domain\NotEnoughtSeatsAvailableException;
use App\Application\Event\Domain\ReservedEventDayService;
use App\Application\Event\Infrastructure\Symfony\Doctrine\AvailableEventDaysObsoleteVersionException;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;
use Throwable;

class ReserveEventDaysService
{
    public function __construct(
        private AvailableEventDayRepository $availableEventDaysRepository,
        private ReservedEventDayService $reservedEventDayService, 
        private EntityManagerInterface $entityManager
    ) {}

    public function reserveSeats(ReserveEventDaysRequest $request) : ReserveEventDaysResponse
    {
        try {
            $eventId = $request->getEventId();
            $startDate = $request->getStartDate();
            $endDate = $request->getEndDate();
            $seatsNumber = $request->getSeatsNumber();
            $reservationId = Uuid::v4();

            $this->reserveEventDaysSeats($eventId, $reservationId, $startDate, $endDate, $seatsNumber);

            return new ReserveEventDaysResponse(
                $request->getEventId(),
                ReserveEventDaysResponse::IS_RESERVED,
                ReserveEventDaysResponse::IS_NO_ERROR,
                'Seats reserved',
            );
        } catch (CouldNotSaveReservedDaysException) {
            return new ReserveEventDaysResponse(
                $request->getEventId(),
                ReserveEventDaysResponse::IS_NOT_RESERVED,
                ReserveEventDaysResponse::IS_ERROR,
                'Seats not reserved. Unknown error.',
            );   
        } catch (AvailableEventDaysObsoleteVersionException) {
            return new ReserveEventDaysResponse(
                $request->getEventId(),
                ReserveEventDaysResponse::IS_NOT_RESERVED,
                ReserveEventDaysResponse::IS_ERROR,
                'Seats not reserved. Too much traffic.',
            );   
        } catch (NotEnoughtSeatsAvailableException | CouldNotReserveSeatsException) {
            return new ReserveEventDaysResponse(
                $request->getEventId(),
                ReserveEventDaysResponse::IS_NOT_RESERVED,
                ReserveEventDaysResponse::IS_ERROR,
                'Seats not reserved. Not enough seats.',
            );            
        } catch (Throwable) {
            return new ReserveEventDaysResponse(
                $request->getEventId(),
                ReserveEventDaysResponse::IS_NOT_RESERVED,
                ReserveEventDaysResponse::IS_ERROR,
                'Seats not reserved. Unknown error.',
            );
        }
    }

    protected function reserveEventDaysSeats(
        Uuid $eventId,
        Uuid $reservationId,
        DateTime $startDate,
        DateTime $endDate,
        int $seatsNumber,
    ) : void {
        $this->entityManager->getConnection()->beginTransaction();
        try {
            $this->availableEventDaysRepository->reserveEventDays(
                $eventId,
                $startDate,
                $endDate,
                $seatsNumber
            );

            $this->reservedEventDayService->reserveEventDays(
                $eventId,
                $reservationId,
                $startDate,
                $endDate,
                $seatsNumber
            );

            $this->entityManager->getConnection()->commit();
        } catch (Throwable $e) {
            $this->entityManager->getConnection()->rollBack();
            throw $e;
        }
    }
}
