<?php

namespace App\Controller;

use App\Entity\Usercadeaux;
use App\Form\UsercadeauxType;
use App\Repository\UsercadeauxRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/usercadeaux')]
class UsercadeauxController extends AbstractController
{
    #[Route('/', name: 'app_usercadeaux_index', methods: ['GET'])]
    public function index(UsercadeauxRepository $usercadeauxRepository): Response
    {
        return $this->render('usercadeaux/index.html.twig', [
            'usercadeauxes' => $usercadeauxRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_usercadeaux_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $usercadeaux = new Usercadeaux();
        $form = $this->createForm(UsercadeauxType::class, $usercadeaux);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($usercadeaux);
            $entityManager->flush();

            return $this->redirectToRoute('app_usercadeaux_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('usercadeaux/new.html.twig', [
            'usercadeaux' => $usercadeaux,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_usercadeaux_show', methods: ['GET'])]
    public function show(Usercadeaux $usercadeaux): Response
    {
        return $this->render('usercadeaux/show.html.twig', [
            'usercadeaux' => $usercadeaux,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_usercadeaux_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Usercadeaux $usercadeaux, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UsercadeauxType::class, $usercadeaux);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_usercadeaux_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('usercadeaux/edit.html.twig', [
            'usercadeaux' => $usercadeaux,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_usercadeaux_delete', methods: ['POST'])]
    public function delete(Request $request, Usercadeaux $usercadeaux, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$usercadeaux->getId(), $request->request->get('_token'))) {
            $entityManager->remove($usercadeaux);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_usercadeaux_index', [], Response::HTTP_SEE_OTHER);
    }
}
