<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DashboardController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route(path: '/dashboard', name: 'app_dashboard')]
    public function __invoke() : Response
    {
        return $this->render('dashboard.html.twig');
    }
}
