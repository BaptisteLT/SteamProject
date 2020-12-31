<?php

namespace App\Repository;

use App\Entity\Craft;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Craft|null find($id, $lockMode = null, $lockVersion = null)
 * @method Craft|null findOneBy(array $criteria, array $orderBy = null)
 * @method Craft[]    findAll()
 * @method Craft[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CraftRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Craft::class);
    }



    /*
    public function findOneBySomeField($value): ?Craft
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    
    /**
    * @return Query
    */
    public function findAllCraftsVerifiedQuery($search)
    {
        /*Faire la query avec les données*/
        $query=$this->createQueryBuilder('c')
        ->innerJoin('App\Entity\Items', 'itm')
        ->andWhere('c.verified = 1');

        if($search->getTitle())
        {
            $query = $query
                ->andWhere('c.titre LIKE :titre')
                ->setParameter('titre','%'.$search->getTitle().'%');
        }
        
        if($search->getcostMin())
        {
            $query = $query
                ->andWhere('c.cost >= :costmin')
                ->setParameter('costmin',$search->getcostMin());
        }
        
        if($search->getcostMax())
        {
            $query = $query
                ->andWhere('c.cost <= :costmax')
                ->setParameter('costmax',$search->getcostMax());
        }
        
        if($search->getDateMin())
        {
            $query = $query
                ->andWhere('c.date_ajout >= :datemin')
                ->setParameter('datemin',$search->getDateMin());
        }
        
        if($search->getDateMax())
        {
            $query = $query
                ->andWhere('c.date_ajout <= :datemax')
                ->setParameter('datemax',$search->getDateMax());
        }
        if($search->getItemsGroup()==null)/*SI LE CHAMP DE RECHERCHE DU GROUPE EST VIDE, ALORS LA RECHERCHE POUR LES STICKERS FONCTIONNERA*/
        {
            if($search->getSlot1())
            {
                $query = $query
                    ->andWhere('c.Slot1 = itm')
                    ->andWhere('itm.Name LIKE :slot1')
                    ->setParameter('slot1','%'.$search->getSlot1().'%');
            }
            if($search->getSlot2())
            {
                $query = $query
                    ->andWhere('c.Slot2 = itm')
                    ->andWhere('itm.Name LIKE :slot2')
                    ->setParameter('slot2','%'.$search->getSlot2().'%');
            }
            if($search->getSlot3())
            {
                $query = $query
                    ->andWhere('c.Slot3 = itm')
                    ->andWhere('itm.Name LIKE :slot3')
                    ->setParameter('slot3','%'.$search->getSlot3().'%');
            }
            if($search->getSlot4())
            {
                $query = $query
                    ->andWhere('c.Slot4 = itm')
                    ->andWhere('itm.Name LIKE :slot4')
                    ->setParameter('slot4','%'.$search->getSlot4().'%');
            }
        }



        
        if($search->getItemsGroup())
        {
            $query = $query 
                /*->innerJoin('App\Entity\ItemsGroup', 'itmGrp')*/
                ->andWhere($query->expr()->orX( /*Permet un where inclusif, voir https://www.doctrine-project.org/projects/doctrine-orm/en/latest/reference/query-builder.html#working-with-querybuilder*/
                    $query->expr()->eq('c.Slot1', 'itm'),
                    $query->expr()->eq('c.Slot2', 'itm'),
                    $query->expr()->eq('c.Slot3', 'itm'),
                    $query->expr()->eq('c.Slot4', 'itm')
                ))

                ->andWhere('itm.Name LIKE :GroupName')
                ->setParameter('GroupName','%'.$search->getItemsGroup().'%');
                /*->andWhere('itm.itemsGroup = itmGrp')
                ->andWhere('itmGrp.Name LIKE :GroupName')
                ->setParameter('GroupName','%'.$search->getItemsGroup().'%');*/

                /*
                CECI EST LA REQUETE DU DESSUS

                SELECT *
                from craft
                inner join items
                inner join items_group
                where (craft.slot1_id = items.id
                or craft.slot2_id = items.id
                or craft.slot3_id = items.id
                or craft.slot4_id = items.id)
                
                and items.items_group_id=items_group.id
                and items_group.name = 'Katowice 2015'
                */
        }


        $query =$query
            ->orderBy('c.date_ajout', 'DESC');



        return $query->getQuery();
    }










    /**
    * @return Query
    */
    public function findAllCraftsQueryAll(): Query/*All*/
    {
        return $this->findAllCrafts()
            ->getQuery();
    }

    private function findAllCrafts(): QueryBuilder
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.date_ajout', 'DESC');
    }





    /**
    * @return Query
    */
    public function findAllCraftsQueryNotVerified(): Query/*Not verified*/
    {
        return $this->findAllCraftsNotVerified()
            ->getQuery();
    }

    private function findAllCraftsNotVerified(): QueryBuilder
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.verified = 0')
            ->orderBy('c.date_ajout', 'DESC');
    }






    /**
    * @return Craft[] Returns an array of Craft objects
    */
    
    public function CraftsFilterByPoints()
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('App\Entity\UserLikeCraft', 'ulc')
            ->addSelect('SUM(ulc.niveau_du_like) as HIDDEN nblikes')
            ->where('c.id=ulc.Craft')
            ->groupBy('ulc.Craft')
            ->orderBy('nblikes', 'DESC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult()
        ;
    }

        /**
    * @return Craft[] Returns an array of Craft objects
    */
    
    public function findAllCraftsOfAUser($idUser)
    {
        return $this->createQueryBuilder('c')
            ->where('c.User=:iduser')
            ->orderBy('c.date_ajout', 'DESC')
            ->setParameter('iduser',$idUser)
            ->getQuery()
            ->getResult()
        ;
    }


    /*SELECT *, SUM(user_like_craft.niveau_du_like) as sommelikes FROM craft
    INNER JOIN user_like_craft ON craft.id = user_like_craft.craft_id
    group by user_like_craft.craft_id
    order by sommelikes DESC*/
    



    
    public function findLineForUser($id, $idUser): array
    {
    $conn = $this->getEntityManager()->getConnection();

    $sql = '
        select * from user_like_craft
        where user_like_craft.user_id = :idUser and user_like_craft.craft_id=:id
        ';
    $stmt = $conn->prepare($sql);
    $stmt->execute(['id' => $id,'idUser' => $idUser]);

    // returns an array of arrays (i.e. a raw data set)
    return $stmt->fetchAll();
    }






    public function updateCraftLikeNiveau($id, $idUser,$niveauDuLike)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            UPDATE user_like_craft
            SET user_like_craft.niveau_du_like = :niveauDuLike
            WHERE user_like_craft.user_id=:idUser AND user_like_craft.craft_id=:id
            ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $id,'idUser' => $idUser,'niveauDuLike' => $niveauDuLike]);
    }






    public function nbLikesPourUnCraft($id)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT SUM(user_like_craft.niveau_du_like)
            FROM user_like_craft
            WHERE user_like_craft.craft_id=:id
            ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $id]);

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();
    }








    public function nbLikesLines($idUser)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT *
            FROM user_like_craft
            WHERE user_like_craft.user_id=:idUser
            ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['idUser' => $idUser]);

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();
    }












    /*Ajax graphs*/
    public function findTheDays($minDate,$maxDate)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT CAST(date_ajout as DATE) FROM `craft`
            WHERE date_ajout BETWEEN :minDate AND :maxDate
            GROUP BY CAST(date_ajout as DATE)
            ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['minDate' => $minDate,'maxDate' => $maxDate]);

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();
    }
    

    
    public function findTheNumbers($minDate,$maxDate)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT COUNT(*) FROM `craft`
            WHERE date_ajout BETWEEN :minDate AND :maxDate
            GROUP BY CAST(date_ajout as DATE)
            ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['minDate' => $minDate,'maxDate' => $maxDate]);

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();
    }
}
