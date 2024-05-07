<?php

namespace App\Controller\mohamedController;

use App\Entity\AppelOffre;
use App\Entity\Stocks;
use App\Entity\User;
use App\Form\AjoutAppelsoffresType;
use App\Form\AppelOffreType;
use App\Form\rechAvanceAppelOffre;
use App\Repository\AppelOffreRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Facebook\Exceptions\FacebookResponseException;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Facebook\Facebook;
use Symfony\Component\Security\Core\Security;
use function PHPUnit\Framework\equalTo;

#[Route('/appeloffre')]
class AppelOffreController extends AbstractController
{


    #[Route('/', name: 'app_appeloffre_index', methods: ['GET', 'POST'])]
    public function index(UserRepository $userRepository, AppelOffreRepository $appelOffreRepository, Request $request,
                          PaginatorInterface $paginator, Security $security): Response
    {

        $searchTerm = $request->query->get('search');
        $user = $this->getUser();
        // Récupérer le rôle de l'utilisateur connecté
        $role = $user->getRoles(); // Supposons que l'utilisateur ait un seul rôle
        if (!empty($role)) {
            $roles = $role[0]; // Récupérer le premier rôle de l'utilisateur
        } else {
            throw new \Exception("L'utilisateur n'a aucun rôle.");
        }
        switch ($roles) {
            case 'ROLE_ADMIN':
                // Logique d'affichage pour l'administrateur
                $apoffre = $appelOffreRepository->findBySearchTerm($searchTerm);
                break;
            case 'ROLE_COLLECTEUR':
                // Logique d'affichage pour le collecteur
                $userId = $user->getId();
                $apoffre = $appelOffreRepository->findBySearchTerm($searchTerm,$userId);
                break;
            case 'ROLE_SOCIETE':
                // Logique d'affichage pour la société
                $userId = $user->getId();
                $apoffre = $appelOffreRepository->findBySearchTerm($searchTerm, null,'En cours');
                break;
            default:
                throw new \Exception("Rôle non pris en charge");
        }

        $pagination = $paginator->paginate(
            $apoffre,
            $request->query->getInt('page', 1),
            5
        );

        // Sélectionner le template approprié en fonction du rôle
        $template = match ($roles) {
            'ROLE_ADMIN' => 'MohamedTemplate/Admin/appel_offre/index.html.twig',
            'ROLE_COLLECTEUR' => 'MohamedTemplate/Collecteur/appel_offre/index.html.twig',
            'ROLE_SOCIETE' => 'MohamedTemplate/Societe/appel_offre/index.html.twig',
        };
        $minPrice = null;
        $maxPrice = null;

        foreach ($apoffre as $appelOffre) {
            $prixinitial = $appelOffre->getPrixinitial();

            if ($minPrice === null || $prixinitial < $minPrice) {
                $minPrice = $prixinitial;
            }

            if ($maxPrice === null || $prixinitial > $maxPrice) {
                $maxPrice = $prixinitial;
            }
        }
        if ($roles=='ROLE_COLLECTEUR'){$pagination=$apoffre;}
        // Afficher les résultats, les transmettre à un template, etc.
        return $this->render($template, [
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
            'appelsoffres' => $pagination,
            'searchTerm' => $searchTerm,
        ]);
    }

    #[Route('/new', name: 'app_appeloffre_new', methods: ['GET', 'POST'])]
    public function new(UserRepository $userRepository,Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        // Récupérer le rôle de l'utilisateur connecté
        $role = $user->getRoles(); // Supposons que l'utilisateur ait un seul rôle
        if (!empty($role)) {
            $roles = $role[0]; // Récupérer le premier rôle de l'utilisateur
        } else {
            throw new \Exception("L'utilisateur n'a aucun rôle.");
        }
        switch ($roles) {
            case 'ROLE_COLLECTEUR':
                // Logique d'affichage pour le collecteur
                break;
            default:
                throw new \Exception("Rôle non pris en charge");
        }
        $articles = $entityManager->createQuery(
            'SELECT s FROM App\Entity\Stocks s WHERE s.appelOffre IS NULL OR s.appelOffre = 0'
        )->getResult();
        $appelOffre = new AppelOffre();
        $appelOffre->getUser($user);
        $form = $this->createForm(AjoutAppelsoffresType::class, $appelOffre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $selectedStockIds = $request->request->get('selectedStockIds');
         dump($request->request->all());
            die(); // ou exit();
            // Enregistrez les données si nécessaire
            $appelOffre->setEtat('En cours');
            $entityManager->persist($appelOffre);
            $entityManager->flush();
            return $this->redirectToRoute('app_appeloffre_index', [], Response::HTTP_SEE_OTHER);
        }

        // Sélectionner le template approprié en fonction du rôle
        $template = match ($roles) {
            'ROLE_COLLECTEUR' => 'MohamedTemplate/Collecteur/appel_offre/new.html.twig',
        };

        return $this->renderForm($template, [
            'appeloffre' => $appelOffre,
            'form' => $form,
            'stockList' => $articles,
        ]);

    }


    #[Route('/{id}', name: 'app_appeloffre_show', methods: ['GET'])]
    public function show(UserRepository $userRepository,AppelOffre $appelOffre): Response
    {


        $user = $this->getUser();
        // Récupérer le rôle de l'utilisateur connecté
        $role = $user->getRoles(); // Supposons que l'utilisateur ait un seul rôle
        if (!empty($role)) {
            $roles = $role[0]; // Récupérer le premier rôle de l'utilisateur
        } else {
            throw new \Exception("L'utilisateur n'a aucun rôle.");
        }
        // Sélectionner le template approprié en fonction du rôle
        $template = match ($roles) {
            'ROLE_ADMIN' => 'MohamedTemplate/Admin/appel_offre/show.html.twig',
            'ROLE_COLLECTEUR' => 'MohamedTemplate/Collecteur/appel_offre/show.html.twig',
            'ROLE_SOCIETE' => 'MohamedTemplate/Societe/appel_offre/show.html.twig',
        };

        return $this->render($template, [
            'appeloffre' => $appelOffre,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_appeloffre_edit', methods: ['GET', 'POST'])]
    public function edit(UserRepository $userRepository,Request $request, AppelOffre $appelOffre, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        // Récupérer le rôle de l'utilisateur connecté
        $role = $user->getRoles(); // Supposons que l'utilisateur ait un seul rôle
        if (!empty($role)) {
            $roles = $role[0]; // Récupérer le premier rôle de l'utilisateur
        } else {
            throw new \Exception("L'utilisateur n'a aucun rôle.");
        }
        switch ($roles) {
            case 'ROLE_COLLECTEUR':
                // Logique d'affichage pour le collecteur
                break;
            default:
                throw new \Exception("Rôle non pris en charge");
        }
        $form = $this->createForm(AppelOffreType::class, $appelOffre, [
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

        // Sélectionner le template approprié en fonction du rôle
        $template = match ($roles) {
            'ROLE_COLLECTEUR' => 'MohamedTemplate/Collecteur/appel_offre/edit.html.twig',
        };

        return $this->renderForm($template, [
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
    #[Route('/{id}/valide', name: 'app_appeloffre_valide', methods: ['GET', 'POST'])]
    public function valide(Request $request, AppelOffre $appelOffre, EntityManagerInterface $entityManager): Response
    {
        $offres = $appelOffre->getOffre();
        foreach ($offres as $offre) {
            if ($offre->getEtat() =="Gagnante" ) {
                $offre->setEtatPayment('paye');
                $offre->setDatePayment(new DateTime());
                $entityManager->persist($offre);
                $entityManager->flush();
            }
        }
        $appelOffre->setEtatPayment('paye');
        $entityManager->persist($appelOffre);
        $entityManager->flush();
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
