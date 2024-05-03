<?php

namespace App\Controller\mohamedController;

use App\Repository\ActiviteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/back')]
class BackEndController extends AbstractController
{
    #[Route('/', name: 'back', methods: ['GET'])]
    public function index(ActiviteRepository $activiteRepository): Response
    {
        return $this->render('FrontTemplate.html.twig');
    }

}
