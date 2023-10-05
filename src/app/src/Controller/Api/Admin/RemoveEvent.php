<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RemoveEvent extends AbstractController
{
    #[Route(path: '/api/event/{id}', name: 'app_delete_event', methods: ["DELETE"])]
    public function __invoke(Request $request, string $id) : Response
    {
        return $this->render('dashboard.html.twig');
    }
}
