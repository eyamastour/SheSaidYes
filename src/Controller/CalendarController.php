<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\planningRepository;
use App\Repository\ServicesRepository;

class CalendarController extends AbstractController
{
    /**
     * @Route("/calendar", name="app_calendar")
     */
    public function index(planningRepository $planning,ServicesRepository $servicesRepository): Response

    {
        $events = $planning->findAll(); 
       // $serv = $servicesRepository->findAll();
        //yparcouri les plannings yhot'hom f tableau
        $rdvs = [];
        foreach($events as $event){
            $rdvs[] = [
            
                    'id' => $event->getIdplan(),
                    'start' => $event->getDateplan(),
                    'title' => $event->getEtatplan(),
                    


            ];

        }
        //Retourne la représentation JSON d'une valeur
        $data = json_encode($rdvs);
        //Crée un tableau à partir de variables et de leur valeur
        return $this->render('calendar/index.html.twig',compact('data'));
    }

}