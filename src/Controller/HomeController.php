<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: 'app_home')]
class HomeController extends AbstractController
{
    /**
     * @return Response
     */
    #[Route('/', name: '_index')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }

    /**
     * @return Response
     */
    #[Route('/mentions-legales', name: '_mentions_legales')]
    public function mentionsLegals(): Response
    {
        return $this->render('home/mentions_legales.html.twig');
    }
}
