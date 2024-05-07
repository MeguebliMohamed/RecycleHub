<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Form\AvisType;
use App\Repository\AvisRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/avis')]
class AvisController extends AbstractController
{
    #[Route('/', name: 'app_avis_index', methods: ['GET'])]
    public function index(AvisRepository $avisRepository): Response
    {
        return $this->render('avis/index.html.twig', [
            'avis' => $avisRepository->findAll(),
        ]);
    }
    #[Route('/front', name: 'app_avis_indexFront', methods: ['GET'])]
    public function indexFront(Request $request, AvisRepository $avisRepository, PaginatorInterface $paginator): Response
    {
        $userId = $this->getUser()->getId();

        // Récupérer les avis de l'utilisateur
        $avis = $avisRepository->findByUserId($userId);

        // Paginer les résultats
        $pagination = $paginator->paginate(
            $avis, // Résultats à paginer
            $request->query->getInt('page', 1), // Numéro de page
            5 // Nombre d'éléments par page
        );

        $role = $this->getUser()->getRoles(); // Supposons que l'utilisateur ait un seul rôle
        if (!empty($role)) {
            $roles = $role[0]; // Récupérer le premier rôle de l'utilisateur
        } else {
            throw new \Exception("L'utilisateur n'a aucun rôle.");
        }
        $template = match ($roles) {
            'ROLE_COLLECTEUR' => 'avis/indexFront.html.twig',
            'ROLE_SOCIETE' => 'avis/indexFrontSociete.html.twig',
        };

        return $this->render($template, [
            'pagination' => $pagination,
        ]);
    }
    #[Route('/new', name: 'app_avis_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $avi = new Avis();
        $user = $this->getUser();
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }
        $avi->setIduser($user);

        $form = $this->createForm(AvisType::class, $avi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($avi);
            $entityManager->flush();

            return $this->redirectToRoute('app_avis_indexFront', [], Response::HTTP_SEE_OTHER);
        }

        $role = $this->getUser()->getRoles(); // Supposons que l'utilisateur ait un seul rôle
        if (!empty($role)) {
            $roles = $role[0]; // Récupérer le premier rôle de l'utilisateur
        } else {
            throw new \Exception("L'utilisateur n'a aucun rôle.");
        }
        $template = match ($roles) {
            'ROLE_COLLECTEUR' => 'avis/new.html.twig',
            'ROLE_SOCIETE' => 'avis/newSociete.html.twig',
        };

        return $this->renderForm($template, [
            'avi' => $avi,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_avis_show', methods: ['GET'])]
    public function show(Avis $avi): Response
    {
        $role = $this->getUser()->getRoles(); // Supposons que l'utilisateur ait un seul rôle
        if (!empty($role)) {
            $roles = $role[0]; // Récupérer le premier rôle de l'utilisateur
        } else {
            throw new \Exception("L'utilisateur n'a aucun rôle.");
        }
        $template = match ($roles) {
            'ROLE_COLLECTEUR' => 'avis/show.html.twig',
            'ROLE_SOCIETE' => 'avis/showSociete.html.twig',
        };

        return $this->render($template, [
            'avi' => $avi,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_avis_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Avis $avi, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AvisType::class, $avi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_avis_indexFront', [], Response::HTTP_SEE_OTHER);
        }
        $role = $this->getUser()->getRoles(); // Supposons que l'utilisateur ait un seul rôle
        if (!empty($role)) {
            $roles = $role[0]; // Récupérer le premier rôle de l'utilisateur
        } else {
            throw new \Exception("L'utilisateur n'a aucun rôle.");
        }
        $template = match ($roles) {
            'ROLE_COLLECTEUR' => 'avis/edit.html.twig',
            'ROLE_SOCIETE' => 'avis/editSociete.html.twig',
        };

        return $this->renderForm($template, [
            'avi' => $avi,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_avis_delete', methods: ['POST'])]
    public function delete(Request $request, Avis $avi, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$avi->getId(), $request->request->get('_token'))) {
            $entityManager->remove($avi);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_avis_index', [], Response::HTTP_SEE_OTHER);
    }
}
