<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
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
        $userId = 1;
        $reclamations = $reclamationRepository->findByUserId($userId);

        return $this->render('reclamation/indexFront.html.twig', [
            'reclamations' => $reclamations,
        ]);
    }
    //preparation statics
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
        $user = $userRepository->find(1);
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

        return $this->renderForm('reclamation/new.html.twig', [
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



        //update entity
        // Update the Reclamation entity
        $reclamation->setStatus('Traiter');
        $reclamation->setUpdateDate(new \DateTime());
        $entityManager->flush();
        try {
            $email = (new TemplatedEmail())
                ->from('azizbenabeda123@gmail.com')
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
        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reclamation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager,UserRepository $userRepository): Response
    {
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);
        $user = $userRepository->find(1);
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
