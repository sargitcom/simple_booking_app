<?php

namespace App\Controller\Api\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GetEventsList extends AbstractController
{
    #[Route(path: '/api/events/{pageNumber}', name: 'app_put_event', methods: ["GET"])]
    public function __invoke(Request $request, string $dateStart, string $dateEnd, int $pageNumber = 1) : Response
    {
        // tutaj odbedzie sie pobranie wszystkich eventow - dla uproszczenia wykonania zadania
        // odczyt odbywa sie z projekcji, wczytywane sa agregaty i rzutowane na tablice, ktora przekazuje w odpowiedzi



        $response = [
            /*'events' => [
                'list' => $events,
                'links' => [
                    // 'getNext' => 'http://localhost:3000/api/events/' . ($pageNumber + 1)
                ]
            ]*/
        ];

        return new JsonResponse($response);
    }
}
