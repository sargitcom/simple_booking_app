<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Welcome extends AbstractController
{
    #[Route(path: '/', name: 'app_welcome', methods: ["GET"])]
    public function __invoke(Request $request) : Response
    {
        return $this->render('welcome.html.twig');
    }
}
