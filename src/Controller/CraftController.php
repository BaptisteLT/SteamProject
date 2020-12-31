<?php

namespace App\Controller;

use App\Entity\Craft;
use App\Form\CraftType;
use App\Form\CraftTypeEdit;
use App\Entity\UserLikeCraft;
use App\Repository\CraftRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;


/**
 * @Route("/craft")
 */
class CraftController extends AbstractController
{
    /**
     * @Route("/", name="craft_index", methods={"GET"})
     */
    public function index(CraftRepository $craftRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $crafts = $paginator->paginate(
            $craftRepository->findAllCraftsQueryAll(),//All crafts
            $request->query->getInt('page', 1), /*page number*/
            9 /*limit per page*/
    );
        return $this->render('craft/index.html.twig', [
            'crafts' => $crafts,
        ]);
    }

    /**
     * @Route("/verify", name="verify", methods={"GET"})
     */
    public function verify(CraftRepository $craftRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $crafts = $paginator->paginate(
            $craftRepository->findAllCraftsQueryNotVerified(),//Only the non-verified in the query
            $request->query->getInt('page', 1), /*page number*/
            9 /*limit per page*/
    );
        return $this->render('craft/index_verify.html.twig', [
            'crafts' => $crafts,
        ]);
    }

    /**
     * @Route("/new", name="craft_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $dateActuelle=new \DateTime('now');
        $craft = new Craft();
        $form = $this->createForm(CraftType::class, $craft);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $craft->setUser($this->getUser());//On set le User pour le craft
            $craft->setDateAjout($dateActuelle);//On set la date d'ajout du craft
            $craft->setUpdatedAt($dateActuelle);//On set le updated at pour l'image
            $craft->setVerified(0);//On met le statut à 0 (pas encore vérifié)

            $entityManager->persist($craft);
            $entityManager->flush();
            $LigneDuLigneParDefaut=new UserLikeCraft();
            $LigneDuLigneParDefaut->setNiveauDuLike(0);
            $LigneDuLigneParDefaut->setUser($this->getUser());
            $LigneDuLigneParDefaut->setCraft($craft);
            $entityManager->persist($LigneDuLigneParDefaut);
            $entityManager->flush();

            return $this->redirectToRoute('history');
        }

        return $this->render('craft/new.html.twig', [
            'craft' => $craft,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="craft_show", methods={"GET"})
     */
    public function show(Craft $craft): Response
    {
        return $this->render('craft/show.html.twig', [
            'craft' => $craft,
        ]);
    }

    /**
     * @Route("/{id}/verify", name="craft_show_verify", methods={"GET"})
     */
    public function show_verify(Craft $craft): Response
    {
        return $this->render('craft/show_verify.html.twig', [
            'craft' => $craft,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="craft_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Craft $craft): Response
    {
        $form = $this->createForm(CraftTypeEdit::class, $craft);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('craft_index');
        }

        return $this->render('craft/edit.html.twig', [
            'craft' => $craft,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}/edit/verify", name="craft_edit_verify", methods={"GET","POST"})
     */
    public function editVerify(Request $request, Craft $craft): Response
    {
        $form = $this->createForm(CraftTypeEdit::class, $craft);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('verify');
        }

        return $this->render('craft/edit_verify.html.twig', [
            'craft' => $craft,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="craft_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Craft $craft,AuthorizationCheckerInterface $authChecker): Response
    {
        if ($this->isCsrfTokenValid('delete'.$craft->getId(), $request->request->get('_token'))) {
            if(($authChecker->isGranted('ROLE_ADMIN'))||(($authChecker->isGranted('ROLE_MOD'))&&($craft->getVerified()==false))){//L'admin peut tout supprimer / Le modérateur ne peut supprimer que les crafts non vérifiés.
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($craft);
            $entityManager->flush();
            }
        }
        return $this->redirectToRoute('craft_index');
    }

        /**
     * @Route("/{id}/deleteverify", name="craft_delete_verify", methods={"DELETE"})
     */
    public function delete_verify(Request $request, Craft $craft,AuthorizationCheckerInterface $authChecker): Response
    {
        if ($this->isCsrfTokenValid('delete'.$craft->getId(), $request->request->get('_token'))) {
            if(($authChecker->isGranted('ROLE_ADMIN'))||(($authChecker->isGranted('ROLE_MOD'))&&($craft->getVerified()==false))){//L'admin peut tout supprimer / Le modérateur ne peut supprimer que les crafts non vérifiés.
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($craft);
            $entityManager->flush();
            }
        }
        return $this->redirectToRoute('verify');
    }



    /**
     * @Route("/craftlikeniveau", name="craft_like_niveau", methods={"GET","POST"})
    */
    public function likeCraftNiveau(Request $request,EntityManagerInterface $em)
    {
        $id = $request->get('id'); //Id du craft
        $niveauDuLike = $request->get('niveaudulike'); //Niveau du like
        $idUser= $this->getUser()->getId(); //Id de l'utilisateur

        $repository = $em->getRepository(Craft::class);
        $likeLine = $repository->findLineForUser($id,$idUser);

        if($likeLine==NULL)
        {
            $craftLikeLine=new UserLikeCraft();

            $craftLikeLine->setUser($this->getUser());

            $repository = $this->getDoctrine()->getRepository(Craft::class); 
            $craft = $repository->find($id); //Trouve le craft

            $craftLikeLine->setCraft($craft);
            $craftLikeLine->setNiveauDuLike($niveauDuLike);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($craftLikeLine);
            $entityManager->flush();
        }
        $repository = $em->getRepository(Craft::class);
        $repository->updateCraftLikeNiveau($id,$idUser,$niveauDuLike);

        $repository = $em->getRepository(Craft::class);
        $nbLikes= $repository->nbLikesPourUnCraft($id);

        $data = array();
        
        array_push($data, $nbLikes[0]['SUM(user_like_craft.niveau_du_like)']); //On push dans l'array le nombre de likes

        $response = new Response(json_encode($data)); //On s'envoie sous format json à la vue
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }


    /**
     * @Route("/crafts_validate", name="crafts_validate", methods={"POST"})
     */
    public function craftsValidate(Request $request,EntityManagerInterface $em)
    {
        /*
         * Action: Ban user from tchat
         * Permet de bannir un user
         */
        if ($request->isXMLHttpRequest()) {     

            $arrayIds = $request->get('crafts');

            $repository = $this->getDoctrine()->getRepository(Craft::class);
            $em = $this->getDoctrine()->getManager();
            foreach($arrayIds as $id){
                
                $craft = $repository->find($id);
                $craft->setVerified(true);
                $em->persist($craft);
                $em->flush();
            }
            
        }
        return new JsonResponse("Created",201);
    }
}
