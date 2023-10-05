<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UpdateEvent extends AbstractController
{
    #[Route(path: '/event/{id}', name: 'app_put_event', methods: ["PUT"])]
    public function __invoke(Request $request, string $id) : Response
    {
        return $this->render('dashboard.html.twig');
    }
}
