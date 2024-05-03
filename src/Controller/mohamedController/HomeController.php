<?php

namespace App\Controller\mohamedController;

use App\Repository\ActiviteRepository;
use App\Service\MajBdService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/home')]
class HomeController extends AbstractController
{
    #[Route('/', name: 'home', methods: ['GET'])]
    public function index(MajBdService $majBdService,ActiviteRepository $activiteRepository): Response
    {
        $majBdService->mettreAJourAppelOffresEtOffres();
        return $this->render('FrontTemplate.html.twig');
    }
}
