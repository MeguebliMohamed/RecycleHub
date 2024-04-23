<?php

namespace App\Controller\mohamedController;

use App\Entity\AppelOffre;
use App\Entity\Stocks;
use App\Form\AjoutAppelsoffresType;
use App\Form\AppelOffreType;
use App\Form\rechAvanceAppelOffre;
use App\Repository\AppelOffreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Facebook\Facebook;
#[Route('/appeloffre')]
class AppelOffreController extends AbstractController
{
    #[Route('/', name: 'app_appeloffre_index', methods: ['GET', 'POST'])]
    public function index(AppelOffreRepository $appelOffreRepository, Request $request): Response
    {
        $apoffre = $appelOffreRepository->findAll();
        $form = $this->createForm(rechAvanceAppelOffre::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            // Réinitialiser le formulaire s'il s'agit d'une action de réinitialisation

            if ($form->isValid()) {
                // Récupérer les données du formulaire
                $data = $form->getData();

                // Construire la requête en fonction des critères de recherche
                $queryBuilder = $appelOffreRepository->createQueryBuilder('e');

                // Ajouter les conditions de recherche en fonction des champs du formulaire
                if (!empty($data['titre'])) {
                    $queryBuilder
                        ->andWhere('e.titre LIKE :titre')
                        ->setParameter('titre', '%' . $data['titre'] . '%');
                }
                if (!empty($data['description'])) {
                    $queryBuilder
                        ->andWhere('e.description LIKE :description')
                        ->setParameter('description', '%' . $data['description'] . '%');
                }
                if (!empty($data['dateFin'])) {
                    $queryBuilder
                        ->andWhere('e.dateFin <= :dateFin')
                        ->setParameter('dateFin', $data['dateFin']);
                }
                if (!empty($data['prixInitial'])) {
                    $queryBuilder
                        ->andWhere('e.prixInitial = :prixInitial')
                        ->setParameter('prixInitial', $data['prixInitial']);
                }

                // Exécuter la requête
                $results = $queryBuilder->getQuery()->getResult();

                // Afficher les résultats, les transmettre à un template, etc.
                return $this->render('appel_offre/index.html.twig', [
                    'appelsoffres' => $results,
                    'recherche' => $form->createView(),
                ]);
            }
        }

// Si le formulaire n'est pas soumis ou n'est pas valide, afficher le formulaire vide
        return $this->render('appel_offre/index.html.twig', [
            'appelsoffres' => $apoffre,
            'recherche' => $form->createView(),
        ]);

        return $this->render('appel_offre/index.html.twig', [
            'appelsoffres' => $apoffre,
            'recherche' => $form->createView(),
        ]);
    }


    #[Route('/new', name: 'app_appeloffre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $articles = $entityManager->createQuery(
            'SELECT s FROM App\Entity\Stocks s WHERE s.appelOffre IS NULL OR s.appelOffre = 0'
        )->getResult();
        $appelOffre = new AppelOffre();
        $form = $this->createForm(AjoutAppelsoffresType::class, $appelOffre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $selectedStockIds = $request->request->get('selectedStockIds');
         //   dump($request->request->all());
           // die(); // ou exit();
            // Enregistrez les données si nécessaire
            $appelOffre->setEtat('En cours');
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
    { $form = $this->createForm(AppelOffreType::class, $appelOffre, [
        'appelOffre' => $appelOffre,
    ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            // Récupérez tous les stocks associés à l'appel d'offre actuel
            $oldStocks = $entityManager->getRepository(Stocks::class)->findBy(['appelOffre' => $appelOffre]);

            // Définissez le champ appelOffre sur null pour les anciens stocks
            foreach ($oldStocks as $oldStock) {
                $oldStock->setAppelOffre(null);
            }

            // Associez chaque nouveau stock à l'appel d'offre
            $selectedStocks = $form->get('stocks')->getData();
            foreach ($selectedStocks as $stock) {
                $stock->setAppelOffre($appelOffre);
            }
            // Mettre à jour l'entité AppelOffre
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


    #[Route('/publish-on-facebook/{id}', name: 'publish_on_facebook')]
    public function publishOnFacebook(Request $request, int $id): Response
    {
        // Récupérer l'appel d'offres depuis la base de données
        $appelOffre = $this->getDoctrine()->getRepository(AppelOffre::class)->find($id);

        // Vérifier si l'appel d'offres existe
        if (!$appelOffre) {
            // Rediriger vers une page d'erreur ou une autre page appropriée
            return $this->redirectToRoute('index');
        }

        // Générer le message à publier sur Facebook en fonction de l'appel d'offres
        $userName = $appelOffre->getUser() ? $appelOffre->getUser()->getUsername() : "Utilisateur inconnu";
        $message = "Nouvel appel d'offres publié par {$userName} : {$appelOffre->getTitre()} - {$appelOffre->getDescription()} - " .
            "Date de début : {$appelOffre->getDateDebut()->format('d/m/Y H:i')} - " .
            "Date de fin : {$appelOffre->getDateFin()->format('d/m/Y H:i')} - " .
            "Prix initial : {$appelOffre->getPrixInitial()}";
        // Remplacez ces valeurs par les vôtres
        $pageId = '230735116796871';
        $accessToken = "EAAKQn0BZCZCqIBO20F7TS7gcjk41gZBF6eq1sEZAtzGBm2ZCpMpZBtC5l2pupMfnmIVl6WvzdpTkLjnvML3rUFLPeaBxnqim1nosgkEOvZBVrQZCZCrtDOb5DpJJb30VMMJnOvzKBz31TgLyQRWafIFiGd5ZCl0eHZBSSj7aiZAb5jNDavxo6tCLvCHg0KvsBmBE7dUZD";

        // Créer un client Facebook
        $facebookClient = new \Facebook\Facebook([
            'app_id' => '721963609751202',
            'app_secret' => '5d1b555428d26fd1b13334a8505f98a6',
            'default_graph_version' => 'v11.0',
        ]);

        // Publier sur la page Facebook
        try {
         $facebookClient->post('/' . $pageId . '/feed', [
            'message' => $message,
            'access_token' => $accessToken,
        ]);
        } catch (FacebookResponseException $e) {
            // Gérer l'erreur
            $errorMessage = $e->getMessage();
            // Log ou affichez l'erreur, ou gérez-la de manière appropriée
            // ...
        }

        // Rediriger vers la page d'index
        return $this->redirectToRoute('app_appeloffre_index');
    }

}
