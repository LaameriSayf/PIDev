<?php

namespace App\Repository;

use App\Entity\Odonnance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Odonnance>
 *
 * @method Odonnance|null find($id, $lockMode = null, $lockVersion = null)
 * @method Odonnance|null findOneBy(array $criteria, array $orderBy = null)
 * @method Odonnance[]    findAll()
 * @method Odonnance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OdonnanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ordonnance::class);
    }

//    /**
//     * @return Odonnance[] Returns an array of Odonnance objects
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

//    public function findOneBySomeField($value): ?Odonnance
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
