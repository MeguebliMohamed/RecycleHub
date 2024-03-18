<?php

namespace App\Controller;

use App\Entity\Appelsoffres;
use App\Form\AppelsoffresType;
use App\Repository\AppelsoffresRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/appelsoffres')]
class AppelsoffresController extends AbstractController
{
    #[Route('/', name: 'app_appelsoffres_index', methods: ['GET'])]
    public function index(AppelsoffresRepository $appelsoffresRepository): Response
    {
        return $this->render('appelsoffres/index.html.twig', [
            'appelsoffres' => $appelsoffresRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_appelsoffres_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $appelsoffre = new Appelsoffres();
        $form = $this->createForm(AppelsoffresType::class, $appelsoffre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($appelsoffre);
            $entityManager->flush();

            return $this->redirectToRoute('app_appelsoffres_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('appelsoffres/new.html.twig', [
            'appelsoffre' => $appelsoffre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_appelsoffres_show', methods: ['GET'])]
    public function show(Appelsoffres $appelsoffre): Response
    {
        return $this->render('appelsoffres/show.html.twig', [
            'appelsoffre' => $appelsoffre,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_appelsoffres_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Appelsoffres $appelsoffre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AppelsoffresType::class, $appelsoffre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_appelsoffres_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('appelsoffres/edit.html.twig', [
            'appelsoffre' => $appelsoffre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_appelsoffres_delete', methods: ['POST'])]
    public function delete(Request $request, Appelsoffres $appelsoffre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$appelsoffre->getId(), $request->request->get('_token'))) {
            $entityManager->remove($appelsoffre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_appelsoffres_index', [], Response::HTTP_SEE_OTHER);
    }
}
