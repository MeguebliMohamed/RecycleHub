<?php

namespace App\Controller\mohamedController;

use App\Entity\AppelOffre;
use App\Entity\Stocks;
use App\Form\AjoutAppelsoffresType;
use App\Form\AppelOffreType;
use App\Repository\AppelOffreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/appeloffre')]
class AppelOffreController extends AbstractController
{
    #[Route('/', name: 'app_appeloffre_index', methods: ['GET'])]
    public function index(AppelOffreRepository $appelOffreRepository): Response
    {
        return $this->render('appel_offre/index.html.twig', [
            'appelsoffres' => $appelOffreRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_appeloffre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $articles = $entityManager->getRepository(Stocks::class)->findAll();
        $appelOffre = new AppelOffre();
        $form = $this->createForm(AjoutAppelsoffresType::class, $appelOffre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrez les données si nécessaire
            $entityManager->persist($appelOffre);
            $entityManager->flush();

            return $this->redirectToRoute('app_appeloffre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('appel_offre/new.html.twig', [
            'appeloffre' => $appelOffre,
            'form' => $form,
            'stockList' => $articles,
        ]);
    }


    #[Route('/{id}', name: 'app_appeloffre_show', methods: ['GET'])]
    public function show(AppelOffre $appelOffre): Response
    {
        return $this->render('appel_offre/show.html.twig', [
            'appeloffre' => $appelOffre,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_appeloffre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, AppelOffre $appelOffre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AppelOffreType::class, $appelOffre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_appeloffre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('appel_offre/edit.html.twig', [
            'appeloffre' => $appelOffre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_appeloffre_delete', methods: ['POST'])]
    public function delete(Request $request, AppelOffre $appelOffre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$appelOffre->getId(), $request->request->get('_token'))) {
            $entityManager->remove($appelOffre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_appeloffre_index', [], Response::HTTP_SEE_OTHER);
    }
}
