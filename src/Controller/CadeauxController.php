<?php

namespace App\Controller;

use App\Entity\Cadeaux;
use App\Form\CadeauxType;
use App\Repository\CadeauxRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/cadeaux')]
class CadeauxController extends AbstractController
{
    #[Route('/', name: 'app_cadeaux_index', methods: ['GET'])]
    public function index(CadeauxRepository $cadeauxRepository): Response
    {
        return $this->render('cadeaux/index.html.twig', [
            'cadeauxes' => $cadeauxRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_cadeaux_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $cadeaux = new Cadeaux();
        $form = $this->createForm(CadeauxType::class, $cadeaux);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($cadeaux);
            $entityManager->flush();

            return $this->redirectToRoute('app_cadeaux_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cadeaux/new.html.twig', [
            'cadeaux' => $cadeaux,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cadeaux_show', methods: ['GET'])]
    public function show(Cadeaux $cadeaux): Response
    {
        return $this->render('cadeaux/show.html.twig', [
            'cadeaux' => $cadeaux,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_cadeaux_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cadeaux $cadeaux, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CadeauxType::class, $cadeaux);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_cadeaux_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cadeaux/edit.html.twig', [
            'cadeaux' => $cadeaux,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cadeaux_delete', methods: ['POST'])]
    public function delete(Request $request, Cadeaux $cadeaux, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cadeaux->getId(), $request->request->get('_token'))) {
            $entityManager->remove($cadeaux);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_cadeaux_index', [], Response::HTTP_SEE_OTHER);
    }
}
