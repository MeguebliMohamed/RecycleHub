<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\User1Type;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profile')]
class ProfileController extends AbstractController
{
    #[Route('', name: 'app_profile_show', methods: ['GET'])]
    public function show(): Response
    {
        $user = $this->getUser();
        return $this->render('profile/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/edit', name: 'app_profile_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $entityManager,UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(User1Type::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('plainPassword')->getData();
            $hashPassword = $passwordHasher->hashPassword($user,$plainPassword);
            $user->setPassword($hashPassword);

            $entityManager->flush();

            return $this->redirectToRoute('app_profile_show', [], Response::HTTP_SEE_OTHER);
        }
        $role = $this->getUser()->getRoles(); // Supposons que l'utilisateur ait un seul rôle
        if (!empty($role)) {
            $roles = $role[0]; // Récupérer le premier rôle de l'utilisateur
        } else {
            throw new \Exception("L'utilisateur n'a aucun rôle.");
        }
        $template = match ($roles) {
            'ROLE_COLLECTEUR' => 'profile/edit.html.twig',
            'ROLE_SOCIETE' => 'profile/editSociete.html.twig',
        };
        return $this->renderForm($template, [
            'user' => $user,
            'form' => $form,
        ]);
    }


}
