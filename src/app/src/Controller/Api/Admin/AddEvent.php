<?php

namespace App\Controller\Api\Admin;

use App\Application\Event\Application\AddEvent\AddEvent as ApplicationAddEvent;
use App\Application\Event\Application\AddEvent\AddEventRequest;
use App\Application\Event\Domain\EventName;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

class AddEvent extends AbstractController
{
    public function __construct(private ApplicationAddEvent $addEvent) {}


    #[Route(path: '/api/event', name: 'app_post_event', methods: ["POST"])]
    public function __invoke(Request $request) : Response
    {

        $data = $request->toArray();
        
        $eventId = Uuid::v4();
        $eventName = EventName::create($data['eventName'] ?? "");

        $addEventRequset = new AddEventRequest($eventId, $eventName);

        $this->addEvent->createEvent($addEventRequset);

        $response = [
            'event' => [
                'eventId' => $eventId->toRfc4122(),
                'links' => [
                    // tutaj linki zgodnie z HATOES https://en.wikipedia.org/wiki/HATEOAS
                    // dla uproszczenia zostawilem puste
                ]
            ]
        ];

        return new JsonResponse($response);
    }
}
