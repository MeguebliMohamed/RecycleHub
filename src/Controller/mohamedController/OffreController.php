<?php

namespace App\Controller\mohamedController;

use App\Entity\AppelOffre;
use App\Entity\Offre;
use App\Form\AjouterOffreType;
use App\Form\OffreType;
use App\Repository\AppelOffreRepository;
use App\Repository\OffreRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/offre')]
class OffreController extends AbstractController
{
    private static ?int $idUser = 1; // Déclaration de la variable statique $id
    #[Route('/', name: 'app_offre_index', methods: ['GET'])]
    public function index(UserRepository $userRepository,OffreRepository $offreRepository,Request $request,
                          PaginatorInterface $paginator, Security $security): Response
    {


        $user = $userRepository->findOneBy(['id' => self::$idUser]);
        //$user = $security->getUser();
        $searchTerm = $request->query->get('search');

        // Récupérer le rôle de l'utilisateur connecté
        $role = $user->getRole(); // Supposons que l'utilisateur ait un seul rôle

        switch ($role) {
            case 'ROLE_ADMIN':
                // Logique d'affichage pour l'administrateur
                $offre = $offreRepository->findBySearchTerm($searchTerm,null,null);
                break;

            case 'ROLE_SOCIETE':
                // Logique d'affichage pour la société
                $userId = $user->getId();
                $offre = $offreRepository->findBySearchTerm($searchTerm, $userId);
                break;
            default:
                throw new \Exception("Rôle non pris en charge");
        }

        $pagination = $paginator->paginate(
            $offre,
            $request->query->getInt('page', 1),
            5
        );
        $minPrice = null;
        $maxPrice = null;
        foreach ($pagination as $Offre) {
            $prixinitial = $Offre->getMontant();

            if ($minPrice === null || $prixinitial < $minPrice) {
                $minPrice = $prixinitial;
            }

            if ($maxPrice === null || $prixinitial > $maxPrice) {
                $maxPrice = $prixinitial;
            }
        }
        // Sélectionner le template approprié en fonction du rôle
        $template = match ($role) {
            'ROLE_ADMIN' => 'MohamedTemplate/Admin/offre/index.html.twig',
            'ROLE_SOCIETE' => 'MohamedTemplate/Societe/offre/index.html.twig',
        };

        return $this->render($template, [
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
            'offres' => $pagination,
            'searchTerm' => $searchTerm,
        ]);

}

    #[Route('/{id}/new', name: 'app_offre_new', methods: ['GET', 'POST'])]
    public function new(UserRepository $userRepository,AppelOffre $appelOffre,Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {

        $user = $userRepository->findOneBy(['id' => self::$idUser]);
        //$user = $security->getUser();
        $offre = new Offre();
        $offre->setUser($user);
        $form = $this->createForm(AjouterOffreType::class, $offre);
        // Récupérer l'AppelOffre associé à partir de l'id

        // Associer l'AppelOffre à l'Offre
        $offre->setAppelOffre($appelOffre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($offre);
            $entityManager->flush();

            return $this->redirectToRoute('app_offre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('MohamedTemplate/Societe/offre/new.html.twig', [
            'offre' => $offre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_offre_show', methods: ['GET'])]
    public function show(UserRepository $userRepository,Offre $offre): Response
    {  $user = $userRepository->findOneBy(['id' => self::$idUser]);
        //$user = $security->getUser();

        // Récupérer le rôle de l'utilisateur connecté
        $role = $user->getRole(); // Supposons que l'utilisateur ait un seul rôle

        // Sélectionner le template approprié en fonction du rôle
        $template = match ($role) {
            'ROLE_ADMIN' => 'MohamedTemplate/Admin/offre/show.html.twig',
            'ROLE_SOCIETE' => 'MohamedTemplate/Societe/offre/show.html.twig',
        };
        return $this->render($template, [
            'offre' => $offre,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_offre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Offre $offre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_offre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('MohamedTemplate/Societe/offre/edit.html.twig', [
            'offre' => $offre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_offre_delete', methods: ['POST'])]
    public function delete(Request $request, Offre $offre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$offre->getId(), $request->request->get('_token'))) {
            $entityManager->remove($offre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_offre_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/checkout', name: 'checkout', methods: ['POST'])]
    public function checkout(Request $request, Offre $offre, EntityManagerInterface $entityManager): Response
    {
        $stripe = new \Stripe\StripeClient('sk_test_51OoTIhIZwMThMQrbvciH7ytahyGar4F8JmIkuuzTFQkxrVfhzO1hWwmyW4ZQErZ72BAUuwxNmIIybQWwCn6xNnzh00sy9kHamj');
        $checkout_session = $stripe->checkout->sessions->create([
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'T-shirt',
                    ],
                    'unit_amount' => 2000,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => 'http://localhost:4242/success',
            'cancel_url' => 'http://localhost:4242/cancel',
        ]);
    }

}

