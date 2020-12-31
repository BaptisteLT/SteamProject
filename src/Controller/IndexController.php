<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Craft;
use App\Entity\Tchat;
use App\Form\TchatType;
use App\Form\TchatBanType;
use App\Entity\CraftSearch;
use App\Form\CraftSearchType;
use App\Repository\CraftRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\MessageBusInterface;

class IndexController extends AbstractController
{

    // * snip

    /**
     * @Route("/logout", name="logout")
     * @throws \RuntimeException
     */
    public function logoutAction()
    {
        throw new \RuntimeException('Logged out.');
    }

    /**
     * @Route("/", name="index")
     */
    public function index(CraftRepository $craftRepository, PaginatorInterface $paginator, Request $request, EntityManagerInterface $em): Response
    {
        /*TCHAT FORM***********************************************/
        /*$tchat=new Tchat();*/
        $formTchat = $this->createForm(TchatType::class/*, $tchat*/);
        $formTchatBan = $this->createForm(TchatBanType::class);
        /*
        $formTchat->handleRequest($request);
        if ($formTchat->isSubmitted() && $formTchat->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($tchat);
            $em->flush();

            return $this->redirectToRoute('index');
        }*/
        /*********************************************** ************/



        /*Query avec filtres qu'on passe ensuite au paginator*/
        $search=new CraftSearch();
        $formCraftSearch=$this->createForm(CraftSearchType::class,$search);
        $formCraftSearch->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository(Craft::class)->findAllCraftsVerifiedQuery($search); 



        
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $idUser = $this->getUser()->getId();
        }
        else
        {
            $idUser=null;
        }
        

        $repository = $em->getRepository(Craft::class);
        $craftsTop = $repository->CraftsFilterByPoints();

        $repository = $em->getRepository(Craft::class);
        $likesLines = $repository->nbLikesLines($idUser);
        

        $crafts = $paginator->paginate(
            //$craftRepository->findAllCraftsVerifiedQuery(),
            $query,
            $request->query->getInt('page', 1), /*page number*/
            12 /*limit per page*/
        );

        $repositoryTchat = $em->getRepository(Tchat::class);
        $lesMessagesNonReversed = $repositoryTchat->GetVingtMessages();
        $messages=array_reverse($lesMessagesNonReversed); //Reverse le tableau pour mettre les résultats les plus récents en bas du tchat
        //dump(intval($this->getUser()->getSteamId()));die;
    return $this->render('index/index.html.twig', [
        'crafts' => $crafts,
        'likesLines' => $likesLines,
        'craftsTop' => $craftsTop,
        'messages'=>$messages,
        'formTchat' => $formTchat->createView(),
        'formTchatBan' => $formTchatBan->createView(),
        'formCraftSearch' => $formCraftSearch->createView(),
    ]);
    }




    /**
     * @Route("/banfromtchat", name="ban_from_tchat", methods={"GET","POST"})
     */
    public function banfromtchat(Request $request,EntityManagerInterface $em)
    {
        /*
         * Action: Ban user from tchat
         * Permet de bannir un user
         */
        if ($request->isXMLHttpRequest()) {         
            $tempsduban = $request->get('tempsduban');
            $steamuserid = $request->get('steamuserid');

            $repository = $this->getDoctrine()->getRepository(User::class);
            $User = $repository->findOneBy(['steamId' => $steamuserid]);//Utilisateur
            
            $nombre_de_bans=$User->getTchatBanCounter();//On récupère le nombre de bans actuels pour le tchat
            $User->setTchatBanCounter($nombre_de_bans+1);//On ajoute un

            $date_actuelle=new \DateTime("now");
            $date_actuelle->modify("+{$tempsduban} minutes"); //On peut ajouter du temps avec un nombre de "days, months, years, hours, minutes and seconds"
            $User->setTchatBan($date_actuelle); //PLUS AJOUTER LE TEMPS EN SECONDES DE $tempsduban

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($User);
            $entityManager->flush();

        }
        $data["erreur"]="ok";

        return new JsonResponse($steamuserid);
    }



    /**
     * @Route("/addmsg", name="addmsg", methods={"POST"})
     */
    public function tchatAddMsg(MessageBusInterface $bus,Request $request,EntityManagerInterface $em)
    {
        /*
         * Action: addMessage
         * Permet l'ajout d'un message
         */
        if ($request->isXMLHttpRequest()) {       
            if($request->get('message')){
                $message = $request->get('message');
                $user =$this->getUser();

                $date=new \DateTime("now");

                $userTchatBan = $user->getTchatBan();

                if($userTchatBan>$date){
                    $data["erreur"]="You are banned from the chat. Expiration: " . $userTchatBan->format('Y-m-d H:i:s');
                    return new JsonResponse($data,403);
                }

                $tchat = new Tchat();
                
                
                $tchat->setUser($user);
                $tchat->setMessage($message);
                $tchat->setDate($date);
                $tchat->setSteamid($this->getUser()->getSteamId());
                
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($tchat);
                $entityManager->flush();
                $data["erreur"]="Created";

                $MessagesArray= "<div class=\"Single_msg\"><div class=\"username\"><p class=\"tchat_p\">".$user."</p><div class=\"steamid hidden\">".$this->getUser()->getSteamid()."</div></div><div class=\"NomDate\"><p class=\"tchat_p tchat_message\">".$message." "."<span class=\"span_date\">".$date->format('H:i')."</span></p></div></div>";
                $data["lastMessages"]=$MessagesArray;
            }
            else{
                $data["erreur"]="Missing parameter";
                return new JsonResponse($data,400);
            }
        }
        
        $update = new Update("http://monsite.com/addmsg",json_encode($data["lastMessages"]));
        $bus->dispatch($update);//En gros dès que la route /addmsg est trigger, on envoit le message pour tout le monde
        
        return new JsonResponse(201);
    }
}
