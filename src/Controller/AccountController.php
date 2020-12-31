<?php

namespace App\Controller;

use App\Entity\Craft;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountController extends AbstractController
{
    /**
     * @Route("/history", name="history")
     */
    public function index(EntityManagerInterface $em)
    {
        $idUser = $this->getUser()->getId(); //Recup l'id de l'user

        $repository = $em->getRepository(Craft::class);
        $allCraftsOfTheUser = $repository->findAllCraftsOfAUser($idUser);//Récupère tous les crafts de l'utilisateur connecté

        return $this->render('account/history.html.twig', [
            'history' => $allCraftsOfTheUser,
        ]);
    }
}
