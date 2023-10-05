<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddEvent extends AbstractController
{
    #[Route(path: '/api/event', name: 'app_post_event', methods: ["POST"])]
    public function __invoke() : Response
    {
        return $this->render('dashboard.html.twig');
    }
}
