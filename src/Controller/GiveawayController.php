<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Giveaway;
use App\Form\GiveawayType;
use App\Repository\GiveawayRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/giveaway")
 */
class GiveawayController extends AbstractController
{


    /**
     * @Route("/", name="giveaway_index", methods={"GET"})
     */
    public function index(GiveawayRepository $giveawayRepository): Response
    {

        return $this->render('giveaway/index.html.twig', [
            'giveaways' => $giveawayRepository->findAllOpen(),
        ]);
    }


    /**
     * @Route("/admin", name="giveaway_index_admin", methods={"GET"})
     */
    public function indexAdmin(GiveawayRepository $giveawayRepository): Response
    {
        return $this->render('giveaway/indexadmin.html.twig', [
            'giveaways' => $giveawayRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="giveaway_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $giveaway = new Giveaway();
        $form = $this->createForm(GiveawayType::class, $giveaway);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($giveaway);
            $entityManager->flush();

            return $this->redirectToRoute('giveaway_index');
        }

        return $this->render('giveaway/new.html.twig', [
            'giveaway' => $giveaway,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="giveaway_show", methods={"GET"})
     */
    public function show(Giveaway $giveaway): Response
    {
        return $this->render('giveaway/show.html.twig', [
            'giveaway' => $giveaway,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="giveaway_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Giveaway $giveaway): Response
    {
        $form = $this->createForm(GiveawayType::class, $giveaway);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('giveaway_index');
        }

        return $this->render('giveaway/edit.html.twig', [
            'giveaway' => $giveaway,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}/join", name="giveaway_join", methods={"GET","POST"})
     */
    public function join(Request $request, Giveaway $giveaway): Response
    {
        
        $giveaway->addUser($this->getUser());
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($giveaway);
        $entityManager->flush();
        /*
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
        
        return $this->redirectToRoute('giveaway_index');
        }*/
        return $this->redirectToRoute('giveaway_index');
    }


     /**
     * @Route("/{id}/pick_winner", name="giveaway_pick_winner", methods={"GET","POST"})
     */
    public function pickwinner(Request $request, Giveaway $giveaway, EntityManagerInterface $em): Response
    {
        $id = $request->get('id');

        $repository = $em->getRepository(Giveaway::class);
        $winnerSteamId = $repository->findOneWinnerForTheGiveaway($id);

        $giveaway->setWinner($winnerSteamId[0]['profile_url']); //Ajoute le gagnant dans le giveaway
        $giveaway->setWinnerTradeurl($winnerSteamId[0]['steam_id']); //Ajoute la trade url du gagnant dans le giveaway
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($giveaway);
        $entityManager->flush();

        return $this->redirectToRoute('giveaway_index');
    }

    /**
     * @Route("/{id}/delete", name="giveaway_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Giveaway $giveaway): Response
    {
        if ($this->isCsrfTokenValid('delete'.$giveaway->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($giveaway);
            $entityManager->flush();
        }

        return $this->redirectToRoute('giveaway_index');
    }
}
