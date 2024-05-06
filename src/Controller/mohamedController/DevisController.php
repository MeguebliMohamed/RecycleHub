<?php

namespace App\Controller\mohamedController;
use App\Entity\AppelOffre;
use App\Entity\Offre;
use App\Entity\User;
use App\Repository\AppelOffreRepository;
use App\Service\GeneratDevisService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DevisController extends AbstractController
{
    private $generatDevisService;

    public function __construct(GeneratDevisService $generatDevisService)
    {
        $this->generatDevisService = $generatDevisService;
    }

    public function generateFactureAction(AppelOffreRepository $appelOffreRepository,Request $request): Response
    {
        $appelOffreId = $request->query->get('id');
        $apoffre = $appelOffreRepository->findAll();
        // Simuler les données pour l'exemple

        $offre = new Offre(/* Données de l'offre */);

        $appelOffreId = $request->query->get('id');
        $apoffre = $appelOffreRepository->find($appelOffreId);
        // Simuler les données pour l'exemple
        $collecteur = $apoffre->getUser();
        $entreprise = $apoffre->getUser();

        $dechets = $apoffre->getStocks()->toArray();

        // Appeler la fonction pour générer la facture
        $filePath = $this->generatDevisService->generateFacture($collecteur, $entreprise, $apoffre, $offre, $dechets);

        // Retourner la réponse avec le fichier PDF généré
        return $this->file($filePath, 'facture.pdf');
    }

    public function generateDevisAction(AppelOffreRepository $appelOffreRepository,Request $request): Response
    {
        $appelOffreId = $request->query->get('id');
        $apoffre = $appelOffreRepository->find($appelOffreId);
        // Simuler les données pour l'exemple
        $collecteur = $apoffre->getUser();
        $entreprise = $apoffre->getUser();

        $dechets = $apoffre->getStocks()->toArray();

        // Appeler la fonction pour générer le devis
        $filePath = $this->generatDevisService->generateDevis($collecteur, $entreprise, $apoffre, $dechets);

        // Retourner la réponse avec le fichier PDF généré
        return $this->file($filePath, 'devis.pdf');
    }
}
