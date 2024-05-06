<?php

namespace App\Repository;

use App\Entity\AppelOffre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AppelOffre>
 *
 * @method AppelOffre|null find($id, $lockMode = null, $lockVersion = null)
 * @method AppelOffre|null findOneBy(array $criteria, array $orderBy = null)
 * @method AppelOffre[]    findAll()
 * @method AppelOffre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppelOffreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AppelOffre::class);
    }

//    /**
//     * @return AppelOffre[] Returns an array of AppelOffre objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AppelOffre
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
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
                $qb->expr()->like('u.titre', ':searchTerm')
            ))
                ->setParameter('searchTerm', '%' . $searchTerm . '%');
        }

        return $qb->getQuery()->getResult();
    }



}
