<?php
namespace App\Tests\Controllers;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IndexControllerTest extends WebTestCase
{

    public function testPage()
    {
        $client=static::createClient();
        $client->request('GET','/');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

}

/*TU PEUX SUPPRIMER CE FICHIER SI TU VEUX C'ETAIT UN TEST*/