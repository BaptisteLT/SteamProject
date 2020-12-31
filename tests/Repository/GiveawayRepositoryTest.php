<?php

namespace App\tests\Repository;

use App\Entity\Giveaway;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GiveawayRepositoryTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /*Pour démarrer les tests:  php bin/phpunit   */
    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }


    public function testFindOneWinnerForTheGiveaway():void
    {
        $id=1;

        $giveaway_winner = $this->entityManager
            ->getRepository(Giveaway::class)
            ->findOneWinnerForTheGiveaway($id)
            ;

        $this->assertCount(1, $giveaway_winner);
    }


    public function testFindAllOpen():void
    {
        $AllOpenGiveaways = $this->entityManager
            ->getRepository(Giveaway::class)
            ->findAllOpen()
            ;

        $this->assertCount(5, $AllOpenGiveaways);
    }




    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}