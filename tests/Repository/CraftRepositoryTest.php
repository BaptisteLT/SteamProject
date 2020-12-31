<?php

namespace App\tests\Repository;

use App\Entity\Craft;
use App\Entity\CraftSearch;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CraftRepositoryTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /*Pour démarrer les tests:  php bin/phpunit   */
    /*php bin/console doctrine:database:create --env=test*/
    /*php bin/console doctrine:schema:create --env=test*/
    /*php bin/console doctrine:fixtures:load*/
    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }


    public function testfindAllCraftsVerifiedQuery():void
    {
        $search = new CraftSearch;
        $search->setTitle("titre");
        $search->setcostMin("99");
        $search->setcostMax("110");
        $search->setDateMin("1500-10-02");
        $search->setDateMax("2150-10-02");
        $search->setSlot1("nvyus");
        $search->setSlot2("Envyu");
        $search->setSlot3("En");
        $search->setSlot4("yus");
        $search->setItemsGroup("Katowice 2015");

        $giveaway_winner = $this->entityManager
            ->getRepository(Craft::class)
            ->findAllCraftsVerifiedQuery($search)
            ->getResult()
            ;

        $this->assertCount(50, $giveaway_winner);
    }





    public function testfindAllCraftsQueryAll():void
    {
        $allCrafts = $this->entityManager
            ->getRepository(Craft::class)
            ->findAllCraftsQueryAll()
            ->getResult()
            ;

        $this->assertCount(100, $allCrafts);
    }




    public function testfindAllCraftsQueryNotVerified():void
    {
        $allCrafts = $this->entityManager
            ->getRepository(Craft::class)
            ->findAllCraftsQueryNotVerified()
            ->getResult()
            ;

        $this->assertCount(50, $allCrafts);
        $this->assertEquals(false,$allCrafts[0]->getVerified()); //Vérifie que les crafts ne sont pas vérifiés (false = pas vérifié)
    }




    public function testCraftsFilterByPoints():void
    {
        $TopCrafts = $this->entityManager
            ->getRepository(Craft::class)
            ->CraftsFilterByPoints()
            ;

        $this->assertCount(3, $TopCrafts);
        $this->assertCount(1, $TopCrafts[0]->getUserLikeCrafts()); //Le nombre 1, signifie qu'un seul utilisateur a liké le craft avec le plus de like ($TopCrafts[0] qui est le plus liké, puis $TopCrafts[1] et $TopCrafts[2])
    }


    public function testfindAllCraftsOfAUser():void
    {
        $id_du_user=1;

        $tousLesCraftsDunlUser = $this->entityManager
            ->getRepository(Craft::class)
            ->findAllCraftsOfAUser($id_du_user)
            ;
    
        $this->assertCount(20, $tousLesCraftsDunlUser); //Signifie que le user a crée 20 crafts
    }


    //Trouve la ligne du like dans la base de données (pour savoir ensuite dans le controlleur si l'utilisateur a déja liké le craft ou non, et créer une ligne si il c'est la première fois qu'il like ce craft)
    public function testfindLineForUser():void
    {
        
        $id_du_user=1;
        $id_du_craft=1;

        $ligne_du_like = $this->entityManager
            ->getRepository(Craft::class)
            ->findLineForUser($id_du_craft,$id_du_user)
            ;

        $this->assertCount(1, $ligne_du_like); //Donc l'utilisateur a déjà liké ce craft. (Il ne peut pas y avoir deux lignes, c'est soit 1 soit 0)
    }



    public function testNbLikesPourUnCraft():void
    {
        $id_du_craft=1;

        $nombre_de_likes_pour_le_craft_1 = $this->entityManager


            ->getRepository(Craft::class)
            ->nbLikesPourUnCraft($id_du_craft)
            ;

        $this->assertCount(1, $nombre_de_likes_pour_le_craft_1); //1 ligne (C'est la somme des likes pour un craft)
        $this->assertEquals(2, $nombre_de_likes_pour_le_craft_1[0]['SUM(user_like_craft.niveau_du_like)']); //la ligne vaut 2 (Donc 2 likes)
        //var_dump($nombre_de_likes_pour_le_craft_1);
    }




    //Va trouver toutes les lignes de like dans la BDD, concernant un utilisateur
    public function testNbLikesLines():void
    {
        $id_du_user=1;

        $nb_de_lignes_de_likes = $this->entityManager


            ->getRepository(Craft::class)
            ->nbLikesLines($id_du_user)
            ;
        $this->assertCount(10, $nb_de_lignes_de_likes); //1 ligne (C'est la somme des likes pour un craft)
        
        //Test de la ligne 10 (array [9])
        $this->assertEquals(1,$nb_de_lignes_de_likes[9]["user_id"]);//Id de l'utilisateur qui a like
        $this->assertEquals(19,$nb_de_lignes_de_likes[9]["craft_id"]);//Id du craft liké
        $this->assertEquals(2,$nb_de_lignes_de_likes[9]["niveau_du_like"]);//Niveau du like

        //var_dump($nb_de_lignes_de_likes);
    }



    
    public function testFindTheDays():void
    {
        $minDate="2020-04-21";
        $maxDate="2020-06-21";

        $TheDaysCraftsHaveBeenCreated = $this->entityManager
            ->getRepository(Craft::class)
            ->findTheDays($minDate,$maxDate)
            ;

        //Les crafts du sites ont été fait du 2020-05-21 au 2020-05-22
        $this->assertCount(2, $TheDaysCraftsHaveBeenCreated); 
        $this->assertEquals("2020-05-21",$TheDaysCraftsHaveBeenCreated[0]["CAST(date_ajout as DATE)"]);
        $this->assertEquals("2020-05-22",$TheDaysCraftsHaveBeenCreated[1]["CAST(date_ajout as DATE)"]);
        //var_dump($TheDaysCraftsHaveBeenCreated);
    }







    //50 crafts le 2020-05-21 et 50 crafts le 2020-05-22
    public function testFindTheNumbers():void
    {
        $minDate="2020-04-21";
        $maxDate="2020-06-21";

        $NumberOfCraftsPerDay = $this->entityManager
            ->getRepository(Craft::class)
            ->findTheNumbers($minDate,$maxDate)
            ;
        $this->assertEquals(50, $NumberOfCraftsPerDay[0]["COUNT(*)"]); //50
        $this->assertEquals(50, $NumberOfCraftsPerDay[1]["COUNT(*)"]); //50

        //var_dump($NumberOfCraftsPerDay);
    }


  

    //L'utilisateur 1 met un like de niveau 3 au craft 1
    public function testupdateCraftLikeNiveau():void
    {
        $id_like_line=1;
        $id_user=1;
        $niveau_du_like=3;
    
        $NumberOfCraftsPerDay = $this->entityManager
            ->getRepository(Craft::class)
            ->updateCraftLikeNiveau($id_like_line, $id_user,$niveau_du_like)
            ;
        
        //var_dump($NumberOfCraftsPerDay);
    }
    


    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}