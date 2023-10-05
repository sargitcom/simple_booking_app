<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddAvailableEventDays extends AbstractController
{
    #[Route(path: '/api/available_event_days', name: 'app_post_available_event_days', methods: ["POST"])]
    public function __invoke() : Response
    {
        return $this->render('dashboard.html.twig');
    }
}
