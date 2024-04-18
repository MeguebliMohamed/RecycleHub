<?php

namespace App\Service;
// src/Service/MajBdService.php


use App\Entity\AppelOffre;
use App\Repository\AppelOffreRepository;
use App\Repository\OffreRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class MajBdService
{
    private AppelOffreRepository $appelOffreRepository;
    private OffreRepository $offreRepository;

    public function __construct(AppelOffreRepository $appelOffreRepository, EntityManagerInterface $entityManager, OffreRepository $offreRepository)
    {
        $this->appelOffreRepository = $appelOffreRepository;
        $this->offreRepository = $offreRepository;
        $this->entityManager = $entityManager;
    }

    public function mettreAJourAppelOffresEtOffres(): void
    {
        $appelOffres = $this->appelOffreRepository->findAll();
        $now = new DateTime();

        foreach ($appelOffres as $appelOffre) {
            // Vérifier si l'appel d'offres n'est pas encore clôturé et que la date de fin est dépassée
            if ($appelOffre->getEtat() != "Cloturer" && $appelOffre->getDateFin() < $now) {
                // Mettre l'état de l'appel d'offres à "Cloturer" et l'état de paiement à "En cours"
                $appelOffre->setEtat("Cloturer");
                $appelOffre->setEtatPayment("En cours");
                $this->entityManager->persist($appelOffre);
                $this->entityManager->flush();
            } elseif ($appelOffre->getEtat() != "Cloturer") {
                // Si l'appel d'offres n'est pas clôturé, mettre l'état de ses offres à "En cours"
                $offres = $this->offreRepository->findByAppelOffre($appelOffre);
                foreach ($offres as $offre) {
                    $offre->setEtat("En cours");
                    $this->entityManager->persist($offre);
                }
                $this->entityManager->flush();
            } elseif ($appelOffre->getEtat() == "Cloturer") {
                // Si l'appel d'offres est clôturé, trouver l'offre gagnante
                $offres = $this->offreRepository->findByAppelOffre($appelOffre);
                $offreGagnante = null;

                if (!empty($offres)) {
                    // Trier les offres par montant décroissant
                    usort($offres, function ($a, $b) {
                        return $a->getMontant() < $b->getMontant();
                    });

                    // La première offre dans la liste triée est l'offre gagnante
                    $offreGagnante = $offres[0];

                    // Mettre à jour l'état de l'offre gagnante en "Gagnante"
                    $offreGagnante->setEtat("Gagnante");
                    $this->entityManager->persist($offreGagnante);
                    $this->entityManager->flush();

                    // Mettre à jour l'état des autres offres en tant que perdantes
                    foreach ($offres as $offre) {
                        if ($offre !== $offreGagnante) {
                            $offre->setEtat("échouées");
                            $this->entityManager->persist($offre);
                            $this->entityManager->flush();
                        }
                    }
                }
            }
        }

    }
}