<?php

namespace App\Service;

use App\Entity\AppelOffre;
use App\Entity\User;
use TCPDF;
use NumberFormatter;

class GeneratDevisService
{  public static function generateDevis(User $collecteur, User $entreprise, AppelOffre $appelOffre, array $dechets) {
    // Générer un numéro de devis unique
    $numeroDevis = uniqid('DEVIS-');

    // Calculer le montant total de la facture
    $montantTotal = self::calculerMontantTotal($dechets);

    // Générer le PDF de la facture
    $pdfFilePath = self::genererFacturePDF($numeroDevis, $collecteur, $entreprise, $appelOffre, $dechets, $montantTotal);

    return $pdfFilePath;
}


    private static function calculerMontantTotal(array $dechets) {
        $montantTotal = 0;
        foreach ($dechets as $dechet) {
            // Supposons que chaque déchet a un prix unitaire associé
            $montantTotal += $dechet->getPrixUnit() * $dechet->getQuantite();
        }
        return $montantTotal;
    }

    private static function genererFacturePDF($numeroDevis, User $collecteur, User $entreprise, AppelOffre $appelOffre, array $dechets, $montantTotal) {
        // Créer une nouvelle instance TCPDF
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Définir les informations du document
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Votre Nom');
        $pdf->SetTitle('Devis');
        $pdf->SetSubject('Devis');
        $pdf->SetKeywords('Devis, facture, TCPDF, PHP');

        // Ajouter une page
        $pdf->AddPage();

        // Écrire le contenu de la facture
        $content = "
            <h1>Devis</h1>
            <p>Numéro de devis : $numeroDevis</p>
            <p>Collecteur : {$collecteur->getNom()} {$collecteur->getPrenom()}</p>
            <p>Entreprise : {$entreprise->getNom()}</p>
            <p>Appel d'offre : {$appelOffre->getTitre()}</p>
            <table border='1'>
                <tr>
                    <th>Déchet</th>
                    <th>Quantité</th>
                    <th>Prix unitaire</th>
                    <th>Total</th>
                </tr>";

        foreach ($dechets as $dechet) {
            $content .= "
                <tr>
                    <td>{$dechet->getNom()}</td>
                    <td>{$dechet->getQuantite()}</td>
                    <td>{$dechet->getPrixUnit()}</td>
                    <td>{{$dechet->getPrixUnit()} * {$dechet->getQuantite()}}</td>
                </tr>";
        }

        $content .= "
                <tr>
                    <td colspan='3'>Montant Total</td>
                    <td>$montantTotal</td>
                </tr>
            </table>";

        // Écrire le contenu dans le PDF
        $pdf->writeHTML($content, true, false, true, false, '');

        return $pdf;
    }

    private static function sauvegarderFacture($pdf, $numeroDevis) {
        // Générer un nom de fichier unique
        $fileName = 'facture_' . $numeroDevis . '.pdf';
        // Générer un nom de fichier temporaire unique
        $tempFileName = tempnam(sys_get_temp_dir(), 'facture_') . '.pdf';

        // Enregistrer le PDF de la facture dans le fichier temporaire
        $pdf->Output($tempFileName, 'F');

        // Spécifier le chemin absolu du répertoire final
        $finalDirectory = 'C:\Users\THINKPAD\PhpstormProjects\RecycleHub\public\uploads\devis\\';
        $finalFilePath = $finalDirectory . $fileName;

        // Vérifier si le répertoire final existe, sinon le créer
        if (!file_exists($finalDirectory)) {
            mkdir($finalDirectory, 0777, true);
        }

        // Déplacer le fichier temporaire vers le répertoire final
        if (!rename($tempFileName, $finalFilePath)) {
            throw new \RuntimeException('Erreur lors du déplacement du fichier temporaire vers le répertoire final.');
        }

        return $finalFilePath;
    }



    public function generateDevis1(User $collecteur, User $entreprise, AppelOffre $appelOffre, array $dechets): string
    {
        $fileName = 'Devis_' . $appelOffre->getId() . '.pdf';

        $document = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $document->AddPage();
        $filePath = "";
        $totalPages = 1;
        $margin = 30;
        $yStart = 750;
        $maxY = 300;
        $nextTextX = $margin;
        $nextTextY = $yStart;
        $currentDate = date('d/m/Y');
        $formatPrix = new NumberFormatter("fr_FR", NumberFormatter::DECIMAL);
        $formatPrix->setAttribute(NumberFormatter::FRACTION_DIGITS, 3);
        $formatUnit = new NumberFormatter("fr_FR", NumberFormatter::DECIMAL);
        $formatUnit->setAttribute(NumberFormatter::FRACTION_DIGITS, 2);

        $document->SetFont('helvetica', 'B', 12);
        $document->SetXY(480, 790);
        $document->Cell(0, 0, "Page " . $totalPages . " / " . $totalPages);

        // Entête du devis
        $document->SetFont('helvetica', 'B', 18);
        $document->SetXY($nextTextX + 170, $nextTextY);
        $document->Cell(0, 0, "Devis N°" . $appelOffre->getId() . "/" . date('Y'));
        $nextTextY -= 50;

        // Informations sur le collecteur
        $document->SetFont('helvetica', '', 12);
        $document->SetXY($nextTextX, $nextTextY);
        $document->Cell(0, 0, "Vendeur:");
        $nextTextY -= 20;
        $document->SetXY($nextTextX, $nextTextY);
        $document->Cell(0, 0, "Nom: " . $collecteur->getNom());
        $nextTextY -= 20;
        $document->SetXY($nextTextX, $nextTextY);
        $document->Cell(0, 0, "Adresse: " . $collecteur->getAdresse());
        $nextTextY += 60;

        // Informations sur l'entreprise
        $document->SetXY($nextTextX + 350, $nextTextY);
        $document->Cell(0, 0, "Client:");
        $nextTextY -= 20;
        $document->SetXY($nextTextX + 350, $nextTextY);
        $document->Cell(0, 0, "Nom: " . $entreprise->getNom());
        $nextTextY -= 20;
        $document->SetXY($nextTextX + 350, $nextTextY);
        $document->Cell(0, 0, "Adresse: " . $entreprise->getAdresse());
        $nextTextY -= 20;

        // Méthode de paiement et date
        $document->SetXY($nextTextX, $nextTextY);
        $document->Cell(0, 0, "Méthode de paiement: Espèces");
        $nextTextY -= 30;
        $document->SetXY($nextTextX, $nextTextY);
        $document->Cell(0, 0, "Date : " . $currentDate);
        $nextTextY -= 30;

        // Liste de Produits
        $document->SetXY($nextTextX, $nextTextY);
        $document->SetFont('helvetica', 'B', 14);
        $document->Cell(0, 0, "Liste de Produits");
        $nextTextY -= 40;

        // En-tête du tableau de description
        $document->SetFont('helvetica', 'B', 12);
        $document->SetXY($nextTextX, $nextTextY);
        $document->Cell(120, 0, "Description", 0, 0, 'C');
        $document->SetXY($nextTextX + 310, $nextTextY);
        $document->Cell(40, 0, "Unité", 0, 0, 'C');
        $document->SetXY($nextTextX + 350, $nextTextY);
        $document->Cell(60, 0, "Quantité", 0, 0, 'C');
        $document->SetXY($nextTextX + 410, $nextTextY);
        $document->Cell(60, 0, "Prix.Un", 0, 0, 'C');
        $document->SetXY($nextTextX + 470, $nextTextY);
        $document->Cell(60, 0, "SubTotal", 0, 0, 'C');
        $nextTextY -= 20;

        $totalDevis = 0;

        foreach ($dechets as $stock) {
            if ($nextTextY < $margin) {
                $totalPages++;
                $document->AddPage();
                $nextTextY = $yStart;

                // En-tête du tableau de description
                $document->SetXY($nextTextX, $nextTextY);
                $document->SetFont('helvetica', 'B', 12);
                $document->Cell(120, 0, "Description", 0, 0, 'C');
                $document->SetXY($nextTextX + 310, $nextTextY);
                $document->Cell(40, 0, "Unité", 0, 0, 'C');
                $document->SetXY($nextTextX + 350, $nextTextY);
                $document->Cell(60, 0, "Quantité", 0, 0, 'C');
                $document->SetXY($nextTextX + 410, $nextTextY);
                $document->Cell(60, 0, "Prix.Un", 0, 0, 'C');
                $document->SetXY($nextTextX + 470, $nextTextY);
                $document->Cell(60, 0, "SubTotal", 0, 0, 'C');
                $nextTextY -= 20;
            }

            $document->SetFont('helvetica', '', 10);
            $document->SetXY($nextTextX, $nextTextY);
            $document->Cell(120, 0, $stock->getNom());
            $document->SetXY($nextTextX + 310, $nextTextY);
            $document->Cell(40, 0, $stock->getUnite());
            $document->SetXY($nextTextX + 350, $nextTextY);
            $document->Cell(60, 0, $formatUnit->format($stock->getQuantite()));
            $document->SetXY($nextTextX + 410, $nextTextY);
            $document->Cell(60, 0, $formatPrix->format($stock->getPrixUnit()));
            $subTotal = $stock->getQuantite() * $stock->getPrixUnit();
            $document->SetXY($nextTextX + 470, $nextTextY);
            $document->Cell(60, 0, $formatPrix->format($subTotal));
            $totalDevis += $subTotal;
            $nextTextY -= 20;
        }

        // Total du devis
        $document->SetXY($nextTextX + 350, $nextTextY);
        $document->Cell(0, 0, "Total du devis :  " . $formatPrix->format($totalDevis) . " TND");

        // Output the PDF to a local file
        $document->Output($fileName, 'F');

        return $fileName;
    }
}
