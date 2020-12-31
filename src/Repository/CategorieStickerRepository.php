<?php

namespace App\Repository;

use App\Entity\CategorieSticker;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CategorieSticker|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategorieSticker|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategorieSticker[]    findAll()
 * @method CategorieSticker[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategorieStickerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategorieSticker::class);
    }

    // /**
    //  * @return CategorieSticker[] Returns an array of CategorieSticker objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CategorieSticker
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
