<?php

namespace App\Controller;

use App\Entity\Items;
use App\Service\ApiItems;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UpdateItemsController extends AbstractController
{
    /**
     * @Route("/updateitems", name="updateitems")
     */
    public function index(ApiItems $ApiItems)
    {

        $items = $ApiItems->getItems();
        //dump($items);die;
        foreach($items['items_list'] as $item)
        {
            

            if(array_key_exists("sticker", $item) && $item['sticker']===1)
            {
                $ItemU=new Items();
                $ItemU->setId($item['classid']);
                $ItemU->setName($item['name']);
                $ItemU->setIconUrl($item['icon_url']);




                if (isset($item["price"]["30_days"]["average"])) {
                    $ItemU->setPrice($item["price"]["30_days"]["average"]);
                    //$Item->setWeaponCondition($item['classid']);
                }
                else{
                    $ItemU->setPrice(null);
                }


                $ItemU->setStickerBool($item['sticker']);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($ItemU);
                $entityManager->flush();
            }


            /*$Item=new Items();
            $Item->setId();
            $Item->setName();
            $Item->setIconUrl();
            $Item->setPrice();
            $Item->setWeaponCondition();
            $Item->setSticker();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($Item);
            $entityManager->flush();*/
        }

        return $this->render('update_items/index.html.twig', [
            'controller_name' => 'UpdateItemsController',
        ]);
    }
}
