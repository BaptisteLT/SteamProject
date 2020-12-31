<?php

namespace App\Repository;

use App\Entity\Giveaway;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Giveaway|null find($id, $lockMode = null, $lockVersion = null)
 * @method Giveaway|null findOneBy(array $criteria, array $orderBy = null)
 * @method Giveaway[]    findAll()
 * @method Giveaway[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GiveawayRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Giveaway::class);
    }

    /**
    * @return Giveaway[] Returns an array of Giveaway objects
    */
    
    public function findAllOpen()
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.Winner IS NULL')
            ->orderBy('g.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    

    /*
    public function findOneBySomeField($value): ?Giveaway
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findOneWinnerForTheGiveaway($id): array
{
    $conn = $this->getEntityManager()->getConnection();

    $sql = '
        SELECT user.profile_url, user.steam_id
        FROM user inner join giveaway_user on user.id = giveaway_user.user_id
        where giveaway_id=:id ORDER BY RAND() LIMIT 1
        ';
    $stmt = $conn->prepare($sql);
    $stmt->execute(['id' => $id]);

    // returns an array of arrays (i.e. a raw data set)
    return $stmt->fetchAll();
}
}
