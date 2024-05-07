<?php

namespace App\Controller\mohamedController;

use App\Service\MajBdService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/home')]
class HomeControle extends AbstractController
{
    #[Route('/', name: 'home', methods: ['GET'])]
    public function index(MajBdService $majBdService): Response
    {
        $majBdService->mettreAJourAppelOffresEtOffres();



        return $this->render('home.html.twig');
    }
}
