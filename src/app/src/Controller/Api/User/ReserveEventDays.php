<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReserveEventDays extends AbstractController
{
    #[Route(path: '/reserved_event_days/{eventId}', name: 'app_post_reserved_event_days', methods: ["POST"])]
    public function __invoke(Request $request, string $eventId) : Response
    {
        return $this->render('dashboard.html.twig');
    }
}