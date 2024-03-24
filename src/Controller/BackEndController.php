<?php

namespace App\Controller;

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
        return $this->render('BackEndTemplate.html.twig');
    }

}
