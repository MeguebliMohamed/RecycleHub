<?php

namespace App\Controller\mohamedController;

use App\Entity\Stocks;
use App\Form\AjouterStockType;
use App\Form\StocksType;
use App\Repository\StocksRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/stocks')]
class StocksController extends AbstractController
{
    private static ?int $idUser = 1; // Déclaration de la variable statique $id

    #[Route('/', name: 'app_stocks_index', methods: ['GET'])]
    public function index(UserRepository $userRepository,StocksRepository $stocksRepository,Request $request,
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
                $offre = $stocksRepository->findBySearchTerm($searchTerm,null,null);
                break;

            case 'ROLE_COLLECTEUR':
                // Logique d'affichage pour la société
                $userId = $user->getId();
                $stock = $stocksRepository->findBySearchTerm($searchTerm, $userId);
                break;
            default:
                throw new \Exception("Rôle non pris en charge");
        }

        $pagination = $paginator->paginate(
            $stock,
            $request->query->getInt('page', 1),
            4
        );
        $minPrice = null;
        $maxPrice = null;
        foreach ($pagination as $O) {
            $prixinitial = $O->getPrixUnit();

            if ($minPrice === null || $prixinitial < $minPrice) {
                $minPrice = $prixinitial;
            }

            if ($maxPrice === null || $prixinitial > $maxPrice) {
                $maxPrice = $prixinitial;
            }
        }
        // Sélectionner le template approprié en fonction du rôle
        $template = match ($role) {
            'ROLE_ADMIN' => 'MohamedTemplate/Admin/stocks/index.html.twig',
            'ROLE_COLLECTEUR' => 'MohamedTemplate/Collecteur/stocks/index.html.twig',
        };

        return $this->render($template, [
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
            'stocks' => $pagination,
            'searchTerm' => $searchTerm,
        ]);
    }

    #[Route('/new', name: 'app_stocks_new', methods: ['GET', 'POST'])]
    public function new(UserRepository $userRepository,Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
        $stock = new Stocks();
        $user = $userRepository->findOneBy(['id' => self::$idUser]);
        //$user = $security->getUser();
        $stock->setUser($user);
        $form = $this->createForm(AjouterStockType::class, $stock);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($stock);
            $entityManager->flush();

            return $this->redirectToRoute('app_stocks_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('MohamedTemplate/Collecteur/stocks/new.html.twig', [
            'stock' => $stock,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_stocks_show', methods: ['GET'])]
    public function show(Stocks $stock): Response
    {
        return $this->render('MohamedTemplate/Collecteur/stocks/show.html.twig', [
            'stock' => $stock,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_stocks_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Stocks $stock, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(StocksType::class, $stock);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_stocks_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('MohamedTemplate/Collecteur/stocks/edit.html.twig', [
            'stock' => $stock,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_stocks_delete', methods: ['POST'])]
    public function delete(Request $request, Stocks $stock, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$stock->getId(), $request->request->get('_token'))) {
            $entityManager->remove($stock);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_stocks_index', [], Response::HTTP_SEE_OTHER);
    }
}
