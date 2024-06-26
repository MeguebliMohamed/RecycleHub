<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

#[Route('/reclamation')]
class ReclamationController extends AbstractController
{


    #[Route('/generate-pdf', name: 'app_reclamation_generate_pdf', methods: ['GET'])]
    public function generatePdf(ReclamationRepository $reclamationRepository): Response
    {
        // Récupérer les réclamations depuis le référentiel
        $reclamations = $reclamationRepository->findAll();

        // Créer le contenu HTML du tableau des réclamations
        $html = $this->renderView('reclamation/pdf_reclamations.html.twig', [
            'reclamations' => $reclamations,
        ]);

        // Créer un objet Dompdf
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);

        // Optionnel : définir la taille du papier et l'orientation
        $dompdf->setPaper('A4', 'portrait');

        // Rendre le PDF
        $dompdf->render();

        // Envoi du PDF en réponse
        return new Response(
            $dompdf->output(),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
            ]
        );}
    #[Route('/', name: 'app_reclamation_index', methods: ['GET'])]
    public function index(ReclamationRepository $reclamationRepository): Response
    {
        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $reclamationRepository->findAll(),
        ]);
    }
    #[Route('/front', name: 'app_reclamation_indexFront', methods: ['GET'])]
    public function indexFront(ReclamationRepository $reclamationRepository): Response
    {
        $userId = $this->getUser()->getId();
        $reclamations = $reclamationRepository->findByUserId($userId);
        $role = $this->getUser()->getRoles(); // Supposons que l'utilisateur ait un seul rôle
        if (!empty($role)) {
            $roles = $role[0]; // Récupérer le premier rôle de l'utilisateur
        } else {
            throw new \Exception("L'utilisateur n'a aucun rôle.");
        }
        $template = match ($roles) {
            'ROLE_COLLECTEUR' => 'reclamation/indexFront.html.twig',
            'ROLE_SOCIETE' => 'reclamation/indexFrontSociete.html.twig',
        };
        return $this->render($template, [
            'reclamations' => $reclamations,
            'user'=> $this->getUser(),
        ]);
    }
    #[Route('/statistiques', name: 'app_statistiques')]
    public function statistiques(ReclamationRepository $reclamationRepository): Response
    {
        $statistiques = $reclamationRepository->countByReclaimTypeAndStatus();

        return $this->render('reclamation/statistique.html.twig', [
            'statistiques' => $statistiques,
        ]);
    }
    #[Route('/new', name: 'app_reclamation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $reclamation = new Reclamation();
        $user = $this->getUser();
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }
        $reclamation->setUser($user);

        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reclamation->setUpdateDate(new \DateTime());
            $reclamation->setSubmissionDate(new \DateTime());// Set the updateDate to now
            $entityManager->persist($reclamation);
            $entityManager->flush();

            return $this->redirectToRoute('app_reclamation_indexFront', [], Response::HTTP_SEE_OTHER);
        }
        $role = $this->getUser()->getRoles(); // Supposons que l'utilisateur ait un seul rôle
        if (!empty($role)) {
            $roles = $role[0]; // Récupérer le premier rôle de l'utilisateur
        } else {
            throw new \Exception("L'utilisateur n'a aucun rôle.");
        }
        $template = match ($roles) {
            'ROLE_COLLECTEUR' => 'reclamation/new.html.twig',
            'ROLE_SOCIETE' => 'reclamation/newSociete.html.twig',
        };
        return $this->renderForm($template, [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    /**
     * @throws TransportExceptionInterface
     */
    #[Route('/traiter', name: 'traiter_reclamation', methods: ['POST'])]
    public function traiter(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer, Environment $twig): Response
    {
        global $logger;
        $reclamationId = $request->request->get('reclamationId');
        $descriptionDuTraitement = $request->request->get('traitementDescription');

        $reclamation = $entityManager->getRepository(Reclamation::class)->find($reclamationId);

        if (!$reclamation) {
            throw $this->createNotFoundException('No reclamation found for id ' . $reclamationId);
        }

        // Update the Reclamation entity
        $reclamation->setStatus('Traiter');
        $reclamation->setUpdateDate(new \DateTime());
        $entityManager->flush();
        try {
            $email = (new TemplatedEmail())
                ->from('atef.mannai@esprit.tn')
                ->to($reclamation->getUser()->getEmail())
                ->subject('Votre Reclamation a été traitée')
                ->htmlTemplate('emails/reclamation_traitement.html.twig')
                ->context([
                    'descriptionDuTraitement' => $descriptionDuTraitement,
                ]);

            // Attempt to send the email
            $mailer->send($email);

            // If successful, add a success flash message
            $this->addFlash('success', 'Reclamation traitée avec succès et email envoyé.');
        } catch (TransportExceptionInterface $e) {
            // Log the error and add an error flash message for the user
            // Assuming you have a logger service, replace `$logger` with your logger instance
            $logger->error(sprintf('Email could not be sent: %s', $e->getMessage()));

            // Add a flash message to inform the user
            $this->addFlash('error', 'Reclamation was processed, but the notification email could not be sent.');
        }

        return $this->redirectToRoute('app_reclamation_index'); // Redirect back to the list
    }

    #[Route('/{id}', name: 'app_reclamation_show', methods: ['GET'])]
    public function show(Reclamation $reclamation): Response
    {
        $role = $this->getUser()->getRoles(); // Supposons que l'utilisateur ait un seul rôle
        if (!empty($role)) {
            $roles = $role[0]; // Récupérer le premier rôle de l'utilisateur
        } else {
            throw new \Exception("L'utilisateur n'a aucun rôle.");
        }
        $template = match ($roles) {
            'ROLE_COLLECTEUR' => 'reclamation/show.html.twig',
            'ROLE_SOCIETE' => 'reclamation/showSociete.html.twig',
        };
        return $this->render($template, [
            'reclamation' => $reclamation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reclamation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager,UserRepository $userRepository): Response
    {
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);
        $user = $this->getUser();
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }


        $reclamation->setUser($user);
        if ($form->isSubmitted() && $form->isValid()) {
            $reclamation->setUpdateDate(new \DateTime());
            $reclamation->setSubmissionDate(new \DateTime());
            $entityManager->flush();

            return $this->redirectToRoute('app_reclamation_indexFront', [], Response::HTTP_SEE_OTHER);
        }
        $role = $this->getUser()->getRoles(); // Supposons que l'utilisateur ait un seul rôle
        if (!empty($role)) {
            $roles = $role[0]; // Récupérer le premier rôle de l'utilisateur
        } else {
            throw new \Exception("L'utilisateur n'a aucun rôle.");
        }
        $template = match ($roles) {
            'ROLE_COLLECTEUR' => 'reclamation/edit.html.twig',
            'ROLE_SOCIETE' => 'reclamation/editSociete.html.twig',
        };
        return $this->renderForm('reclamation/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reclamation_delete', methods: ['POST'])]
    public function delete(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reclamation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reclamation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reclamation_indexFront', [], Response::HTTP_SEE_OTHER);
    }
}
