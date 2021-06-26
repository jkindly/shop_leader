<?php

namespace App\Repository;

use App\Entity\DescriptionBottom;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DescriptionBottom|null find($id, $lockMode = null, $lockVersion = null)
 * @method DescriptionBottom|null findOneBy(array $criteria, array $orderBy = null)
 * @method DescriptionBottom[]    findAll()
 * @method DescriptionBottom[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DescriptionBottomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DescriptionBottom::class);
    }

    // /**
    //  * @return DescriptionBottom[] Returns an array of DescriptionBottom objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DescriptionBottom
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
