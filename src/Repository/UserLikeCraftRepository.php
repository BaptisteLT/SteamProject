<?php

namespace App\Repository;

use App\Entity\UserLikeCraft;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserLikeCraft|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserLikeCraft|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserLikeCraft[]    findAll()
 * @method UserLikeCraft[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserLikeCraftRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserLikeCraft::class);
    }

    // /**
    //  * @return UserLikeCraft[] Returns an array of UserLikeCraft objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserLikeCraft
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
