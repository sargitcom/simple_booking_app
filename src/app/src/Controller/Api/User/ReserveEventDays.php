<?php

namespace App\Controller\Api\User;

use App\Application\Event\Application\ReserveEventDays\ReserveEventDaysRequest;
use App\Application\Event\Application\ReserveEventDays\ReserveEventDays as ReserveEventDaysApplication;
use App\Application\Event\Domain\EventDaySeats;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;
use Throwable;

class ReserveEventDays extends AbstractController
{
    public function __construct(private ReserveEventDaysApplication $reserveEventDaysService) {}

    #[Route(
        path: '/api/reserved_event_days/{eventId}',
        name: 'app_post_reserved_event_days',
        methods: ["POST"]
    )]
    public function __invoke(Request $request, string $eventId) : Response
    {
        try {
            $data = $request->toArray();

            $eventId = Uuid::fromString($eventId ?? "");
            $startDate = new DateTime($data['startAt'] ?? "");
            $endDate = new DateTime($data['endAt'] ?? "");
            $seatsNumber = EventDaySeats::create($data['seats'] ?? "");
    
            $reserveRequest = new ReserveEventDaysRequest($eventId, $startDate, $endDate, $seatsNumber);
    
            $serviceResponse = $this->reserveEventDaysService->reserveSeats($reserveRequest);
    
            $response = [
                'isError' => $serviceResponse->isError(),
                'msg' => $serviceResponse->getMessage(),
                'event' => [
                    'reservation' => [
                        'eventId' => $eventId,
                    ]
                ],
                'links' => [
                    // 'cancelEventSeats' => 'http://localhost:3000/event/seats/1', // DELETE
                ]
            ];
    
            return new JsonResponse($response);
        } catch (Throwable $e) {
            return new JsonResponse([
                'msg' => 'Uknown error',
                'isError' => true,
            ]);
        }        
    }
}
