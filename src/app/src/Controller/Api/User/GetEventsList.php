<?php

namespace App\Controller\Api\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GetEventsList extends AbstractController
{
    #[Route(path: '/events/{pageNumber}', name: 'app_put_event', methods: ["PUT"])]
    public function __invoke(Request $request, int $pageNumber = 1) : Response
    {
        return $this->render('dashboard.html.twig');
    }
}
