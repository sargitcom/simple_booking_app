<?php

namespace App\Application\Event\Application;

use App\Application\Event\Domain\AvailableEventDayRepository;
use Throwable;

class ReserveEventDaysService
{
    public function __construct(private AvailableEventDayRepository $availableEventDaysRepository) {}

    public function reserveSeats(ReserveEventDaysRequest $request) : ReserveEventDaysResponse
    {
        try {
            $uuid = $request->getEventId();
            $startDate = $request->getStartDate();
            $endDate = $request->getEndDate();
            $seatsNumber = $request->getSeatsNumber();

            $this->availableEventDaysRepository->reserveEventDays(
                $uuid,
                $startDate,
                $endDate,
                $seatsNumber
            );

            return new ReserveEventDaysResponse(
                $request->getEventId(),
                ReserveEventDaysResponse::IS_RESERVED,
                ReserveEventDaysResponse::IS_NO_ERROR
            );
        } catch (Throwable $e) {

            var_dump($e->getMessage());
            die;

            return new ReserveEventDaysResponse(
                $request->getEventId(),
                ReserveEventDaysResponse::IS_NOT_RESERVED,
                ReserveEventDaysResponse::IS_ERROR
            );
        }     
    }  
}
