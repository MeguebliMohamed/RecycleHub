<?php

namespace App\Controller\mohamedController;

use App\Entity\AppelOffre;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use TCPDF;

class PdfcaisseController extends AbstractController
{
    #[Route('/generate-invoice/{id}', name: 'generate_invoice')]
    public function generateInvoice(AppelOffre $appelOffre, EntityManagerInterface $entityManager): Response
    {
        // Instantiate TCPDF
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator('Your Company Name');
        $pdf->SetAuthor('Your Company Name');
        $pdf->SetTitle('Facture');
        $pdf->SetSubject('Facture');
        $pdf->SetKeywords('Facture, ' . $appelOffre->getId());

        // Add a page
        $pdf->AddPage();

        // Set font
        $pdf->SetFont('helvetica', '', 12);

        // Add header
        $pdf->Cell(0, 10, 'Facture', 0, 1, 'C');
        $pdf->Ln(10);

        // Add invoice details
        $pdf->Cell(0, 10, 'Propriétaire: ' . $appelOffre->getUser()->getUserName(), 0, 1);
        $pdf->Cell(0, 10, 'Numéro de devis: ' . $appelOffre->getId(), 0, 1);
        $pdf->Cell(0, 10, 'Date: ' . date('Y-m-d'), 0, 1);
        $pdf->Ln(10);

        // Add table headers
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(70, 10, 'Article', 1, 0, 'C');
        $pdf->Cell(40, 10, 'Quantité', 1, 0, 'C');
        $pdf->Cell(40, 10, 'Prix unitaire', 1, 0, 'C');
        $pdf->Cell(40, 10, 'Total', 1, 1, 'C');

        // Add invoice items
        $pdf->SetFont('helvetica', '', 12);
        foreach ($appelOffre->getStocks() as $stock) {
            $pdf->Cell(70, 10, $stock->getNom(), 1, 0);
            $pdf->Cell(40, 10, $stock->getQuantite(), 1, 0, 'C');
            $pdf->Cell(40, 10, number_format($stock->getPrixUnit(), 2), 1, 0, 'C');
            $pdf->Cell(40, 10, number_format($stock->getQuantite() * $stock->getPrixUnit(), 2), 1, 1, 'C');
        }

        // Add total amount
        $pdf->Ln(10);
        $pdf->Cell(150, 10, 'Montant total:', 1, 0, 'R');
        $pdf->Cell(40, 10, number_format($appelOffre->getPrixInitial(), 2), 1, 1, 'C');

        // Output PDF
        $pdfContent = $pdf->Output('invoice_' . $appelOffre->getId() . '.pdf', 'S');

        // Symfony response with the PDF content
        $response = new Response($pdfContent);
        $response->headers->set('Content-Type', 'application/pdf');

        return $response;
    }
}
