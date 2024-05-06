<?php

namespace App\Repository;

use App\Entity\Cadeaux;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Cadeaux>
 *
 * @method Cadeaux|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cadeaux|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cadeaux[]    findAll()
 * @method Cadeaux[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CadeauxRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cadeaux::class);
    }

//    /**
//     * @return Cadeaux[] Returns an array of Cadeaux objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Cadeaux
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
