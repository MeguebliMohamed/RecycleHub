<?php

namespace App\Repository;

use App\Entity\Offre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Offre>
 *
 * @method Offre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Offre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Offre[]    findAll()
 * @method Offre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OffreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Offre::class);
    }

//    /**
//     * @return Offre[] Returns an array of Offre objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Offre
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function findBySearchTerm($searchTerm, $userId = null,$etat=null)
    {
        $qb = $this->createQueryBuilder('u');

        if ($userId !== null) {
            $qb->andWhere('u.user = :userId')
                ->setParameter('userId', $userId);
        }
        if ($etat !== null) {
            $qb->andWhere('u.etat = :etat')
                ->setParameter('etat', $etat);
        }

        if ($searchTerm) {
            $qb->andWhere($qb->expr()->orX(
                $qb->expr()->like('u.description', ':searchTerm')
            ))
                ->setParameter('searchTerm', '%' . $searchTerm . '%');
        }

        return $qb->getQuery()->getResult();
    }
    /**
     * Retourne les offres associées à un appel d'offre spécifique.
     *
     * @param AppelOffre $appelOffre L'appel d'offre pour lequel récupérer les offres.
     * @return Offre[] Un tableau d'offres associées à l'appel d'offre.
     */
    public function findByAppelOffre(AppelOffre $appelOffre): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.appelOffre = :appelOffre')
            ->setParameter('appelOffre', $appelOffre)
            ->getQuery()
            ->getResult();
    }

}
