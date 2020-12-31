<?php

namespace App\tests\Repository;

use App\Entity\Tchat;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TchatRepositoryTest extends KernelTestCase
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


    public function testGetVingtMessages()
    {
        $tchats = $this->entityManager
            ->getRepository(Tchat::class)
            ->GetVingtMessages()
            ;

        $this->assertCount(20, $tchats);
    }




    public function testGetLastMessage()
    {
        $lastMessage = $this->entityManager
            ->getRepository(Tchat::class)
            ->GetLastMessage()
            ;

        $this->assertCount(1, $lastMessage);
    }




    //Simule cette méthode et récupère un seul message (Message après le dernier message affiché)
    public function testGetAllMessagesApresId()
    {
        $lastMessage = $this->entityManager
            ->getRepository(Tchat::class)
            ->GetLastMessage()
            ;
        $lastId=$lastMessage[0]->getId() - 1;

        $MessagesAfterLastId = $this->entityManager
            ->getRepository(Tchat::class)
            ->GetAllMessagesApresId($lastId)
            ;

        $this->assertCount(1, $MessagesAfterLastId);
    }



    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}