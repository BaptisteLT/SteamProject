<?php

namespace App\Repository;

use App\Entity\Tchat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Tchat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tchat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tchat[]    findAll()
 * @method Tchat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TchatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tchat::class);
    }

    // /**
    //  * @return Tchat[] Returns an array of Tchat objects
    //  */
    
    public function GetVingtMessages()
    {
        return $this->createQueryBuilder('t')
            ->orderBy('t.id', 'DESC')
            ->setMaxResults(20)
            ->getQuery()
            ->getResult()
        ;
    }

    public function GetLastMessage()
    {
        return $this->createQueryBuilder('t')
            ->orderBy('t.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
        ;
    }


    public function GetAllMessagesApresId($id)
    {
        return $this->createQueryBuilder('t')
            ->where('t.id > :id')
            ->orderBy('t.id', 'DESC')
            ->setParameter('id',$id)
            ->getQuery()
            ->getResult()
        ;
    }
    

    /*
    public function findOneBySomeField($value): ?Tchat
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
