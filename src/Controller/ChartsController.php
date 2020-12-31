<?php

namespace App\Controller;

use App\Entity\Craft;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ChartsController extends AbstractController
{
    /**
     * @Route("/charts", name="charts")
     */
    public function index()
    {

        return $this->render('charts/index.html.twig', [
            'controller_name' => 'ChartsController',
        ]);
    }


    /**
     * @Route("/chartajax", name="chart_ajax", methods={"GET","POST"})
     */
    public function ChartAjax(Request $request,EntityManagerInterface $em)
    {
        $minDate = $request->get('minDate'); //Date mini
        $maxDate = $request->get('maxDate'); //Date maxi

        /*
        2015-01-31
        2022-01-31
        */

        $repository = $this->getDoctrine()->getRepository(Craft::class);

        $craftsDays = $repository->findTheDays($minDate,$maxDate); //Trouve la ligne jours

        $craftsNumbers=$repository->findTheNumbers($minDate,$maxDate); //Trouve la ligne nombre pour le jour

        $data = array(); //Array qui va contenir arrayDays[] et arrayNumbers[]

        $arrayDays=array(); //Va stocker les jours
        $arrayNumbers=array(); //Va stocker les nombres pour le jour

        foreach($craftsDays as $day)
        {
            array_push($arrayDays, array_values($day)); //On push dans l'array les jours pour le champ "Labels"                       ['01-15-2015','02-15-2015','03-15-2015','04-15-2015',]
        }
        foreach($craftsNumbers as $number)
        {
            array_push($arrayNumbers, array_values($number)); //On push dans l'array les nombres pour le champ "Labels"               ['01-15-2015','02-15-2015','03-15-2015','04-15-2015',]
        }
        array_push($data, $arrayDays,$arrayNumbers);//On push les deux arrays dans un seul.
        
        return new JsonResponse($data);
    }
}
