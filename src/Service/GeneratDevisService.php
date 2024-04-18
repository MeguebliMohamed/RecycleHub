<?php

namespace App\Service;

use App\Entity\AppelOffre;
use App\Entity\Offre;
use App\Entity\Stocks;
use App\Entity\User;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class GeneratDevisService
{
    private $params;
    private $router;

    public function __construct(ParameterBagInterface $params, UrlGeneratorInterface $router)
    {
        $this->params = $params;
        $this->router = $router;
    }

    public function generateFacture(User $collecteur, User $entreprise, AppelOffre $appelOffre, Offre $offre, array $dechets): string
    {
        $filePath = "";

        // Initialisez les variables et formateurs nécessaires
        $totalPages = 1;
        $margin = 30;
        $maxY = 300;
        $currentY = $margin;

        // Créez un nouveau document PDF
        $document = $this->createPDFDocument($appelOffre);

        try {
            // Entête de la facture
            $this->writeHeader($document, "Facture N°" . $appelOffre->getId() . "/" . date('Y'), $currentY);
            $currentY += 10;

            // Informations sur le collecteur
            $this->writeUserInfo($document, $collecteur, $entreprise, $currentY);
            $currentY += 25;

            // Autres informations
            $this->writeAdditionalInfo($document, $offre, $currentY);
            $currentY += 10;

            // Liste des produits
            $this->writeProductList($document, $dechets, $currentY);
            $currentY += count($dechets) * 6 + 20;

            // Coût de la main-d'œuvre
            $prixUnitaireMainOeuvre = $offre->getMontant() - $appelOffre->getPrixInitial();
            $this->writeLaborCost($document, $prixUnitaireMainOeuvre, $currentY);
            $currentY += 25;

            // Total de la facture
            $totalFacture = $this->calculateTotalInvoice($dechets, $prixUnitaireMainOeuvre);
            $this->writeTotalInvoice($document, $totalFacture, $currentY);

            // Générez le fichier PDF
            $filePath = $this->savePDF($document, 'factures', $appelOffre);
        } catch (\Exception $e) {
            // Gérer les exceptions ici
            echo 'Erreur : ', $e->getMessage(), "\n";
        }

        return $filePath;
    }

    public function generateDevis(User $collecteur, User $entreprise, AppelOffre $appelOffre, array $dechets): string
    {
        $filePath = "";

        // Initialisez les variables et formateurs nécessaires
        $totalPages = 1;
        $margin = 30;
        $maxY = 300;
        $currentY = $margin;

        // Créez un nouveau document PDF
        $document = $this->createPDFDocument($appelOffre);

        try {
            // Entête du devis
            $this->writeHeader($document, "Devis N°" . $appelOffre->getId() . "/" . date('Y'), $currentY);
            $currentY += 10;

            // Informations sur le collecteur
            $this->writeUserInfo($document, $collecteur, $entreprise, $currentY);
            $currentY += 25;

            // Liste des produits
            $this->writeProductList($document, $dechets, $currentY);
            $currentY += count($dechets) * 6 + 20;

            // Total du devis
            $totalDevis = $this->calculateTotalQuotation($dechets);
            $this->writeTotalQuotation($document, $totalDevis, $currentY);

            // Générez le fichier PDF
            $filePath = $this->savePDF($document, 'devis', $appelOffre);
        } catch (\Exception $e) {
            // Gérer les exceptions ici
            echo 'Erreur : ', $e->getMessage(), "\n";
        }

        return $filePath;
    }

    private function createPDFDocument(AppelOffre $appelOffre)
    {
        $document = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $document->SetCreator('GeneratDevisService');
        $document->SetTitle('Document_' . $appelOffre->getId());
        $document->SetSubject('Document_' . $appelOffre->getId());
        $document->SetKeywords('PDF, RecycleHub');
        $document->AddPage();
        return $document;
    }

    private function writeHeader($document, $headerText, &$currentY)
    {
        $document->SetFont('helvetica', 'B', 14);
        $document->SetXY(30, $currentY);
        $document->Write(0, $headerText);
        $currentY += 10;
    }

    private function writeUserInfo($document, $collecteur, $entreprise, &$currentY)
    {
        // Informations sur le collecteur
        $document->SetFont('helvetica', '', 12);
        $document->SetXY(30, $currentY);
        $document->Write(0, "Vendeur:");
        $document->SetXY(50, $currentY + 7);
        $document->Write(0, "Nom: " . $collecteur->getNom());
        $document->SetXY(50, $currentY + 14);
        $document->Write(0, "Adresse: " . $collecteur->getAdresse());

        // Informations sur l'entreprise
        $document->SetXY(180, $currentY);
        $document->Write(0, "Client:");
        $document->SetXY(200, $currentY + 7);
        $document->Write(0, "Nom: " . $entreprise->getNom());
        $document->SetXY(200, $currentY + 14);
        $document->Write(0, "Adresse: " . $entreprise->getAdresse());
        $currentY += 25;
    }

    private function writeAdditionalInfo($document, $offre, &$currentY)
    {
        $currentDate = new \DateTime();
        $formattedDate = $currentDate->format('d/m/Y');
        $document->SetXY(30, $currentY);
        $document->Write(0, "Méthode de paiement: Espèces");
        $document->SetXY(180, $currentY);
        $document->Write(0, "Date: " . $formattedDate);
        $currentY += 10;
    }

    private function writeProductList($document, $dechets, &$currentY)
    {
        $document->SetXY(30, $currentY);
        $document->SetFont('helvetica', 'B', 12);
        $document->Write(0, "Liste de Produits");
        $currentY += 10;

        $header = array('Description', 'Unité', 'Quantité', 'Prix.Unit', 'SubTotal');
        $w = array(120, 40, 50, 40, 40);

        foreach ($header as $key => $title) {
            $document->Cell($w[$key], 7, $title, 1, 0, 'C');
        }
        $document->Ln();

        foreach ($dechets as $stock) {
            $document->Cell($w[0], 6, $stock->getNom(), 'LR', 0, 'L');
            $document->Cell($w[1], 6, $stock->getUnite(), 'LR', 0, 'C');
            $document->Cell($w[2], 6, number_format($stock->getQuantite(), 2, ',', ''), 'LR', 0, 'C');
            $document->Cell($w[3], 6, number_format($stock->getPrixUnit(), 3, ',', ''), 'LR', 0, 'C');
            $subTotal = $stock->getQuantite() * $stock->getPrixUnit();
            $document->Cell($w[4], 6, number_format($subTotal, 3, ',', ''), 'LR', 0, 'C');
            $document->Ln();
        }
        $document->Cell(array_sum($w), 0, '', 'T');
        $document->Ln();
    }

    private function writeLaborCost($document, $prixUnitaireMainOeuvre, &$currentY)
    {
        $document->SetXY(30, $currentY);
        $document->Write(0, "Main-d'œuvre:");
        $document->SetXY(200, $currentY);
        $document->Write(0, "Un");
        $document->SetXY(240, $currentY);
        $document->Write(0, "1");
        $document->SetXY(280, $currentY);
        $document->Write(0, number_format($prixUnitaireMainOeuvre, 3, ',', ''));
        $document->SetXY(320, $currentY);
        $document->Write(0, number_format($prixUnitaireMainOeuvre, 3, ',', ''));
    }

    private function writeTotalInvoice($document, $totalFacture, &$currentY)
    {
        $document->SetXY(30, $currentY);
        $document->Write(0, "Total de la facture: " . number_format($totalFacture, 3, ',', '') . " TND");
    }

    private function writeTotalQuotation($document, $totalDevis, &$currentY)
    {
        $document->SetXY(30, $currentY);
        $document->Write(0, "Total du devis: " . number_format($totalDevis, 3, ',', '') . " TND");
    }

    private function calculateTotalInvoice($dechets, $prixUnitaireMainOeuvre)
    {
        $totalFacture = array_reduce($dechets, function ($acc, $stock) {
            return $acc + ($stock->getQuantite() * $stock->getPrixUnit());
        }, $prixUnitaireMainOeuvre);
        return $totalFacture;
    }

    private function calculateTotalQuotation($dechets)
    {
        $totalDevis = array_reduce($dechets, function ($acc, $stock) {
            return $acc + ($stock->getQuantite() * $stock->getPrixUnit());
        }, 0);
        return $totalDevis;
    }

    private function savePDF($document, $folder, $appelOffre)
    {
        $filePath = $this->params->get('kernel.project_dir') . '/public/uploads/' . $folder . '/' . ucfirst($folder) . '_' . $appelOffre->getId() . '.pdf';
        $document->Output($filePath, 'F');
        return $filePath;
    }
}
