<?php

namespace App\Controller\mohamedController;

use App\Entity\Stocks;
use App\Form\AjouterStockType;
use App\Form\StocksType;
use App\Repository\StocksRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/stocks')]
class StocksController extends AbstractController
{
    #[Route('/', name: 'app_stocks_index', methods: ['GET'])]
    public function index(StocksRepository $stocksRepository): Response
    {
        return $this->render('stocks/index.html.twig', [
            'stocks' => $stocksRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_stocks_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $stock = new Stocks();
        $form = $this->createForm(AjouterStockType::class, $stock);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($stock);
            $entityManager->flush();

            return $this->redirectToRoute('app_stocks_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('stocks/new.html.twig', [
            'stock' => $stock,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_stocks_show', methods: ['GET'])]
    public function show(Stocks $stock): Response
    {
        return $this->render('stocks/show.html.twig', [
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

        return $this->renderForm('stocks/edit.html.twig', [
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
