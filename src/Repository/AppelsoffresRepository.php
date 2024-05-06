<?php

namespace App\Repository;

use App\Entity\Appelsoffres;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Appelsoffres>
 *
 * @method Appelsoffres|null find($id, $lockMode = null, $lockVersion = null)
 * @method Appelsoffres|null findOneBy(array $criteria, array $orderBy = null)
 * @method Appelsoffres[]    findAll()
 * @method Appelsoffres[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppelsoffresRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Appelsoffres::class);
    }

//    /**
//     * @return Appelsoffres[] Returns an array of Appelsoffres objects
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

//    public function findOneBySomeField($value): ?Appelsoffres
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
