<?php

namespace App\Controller;

use App\Entity\Reservationservice;
use App\Entity\Services;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ControllerReservationServiceController extends AbstractController
{

      /******** Affichage reservationService *************/

    /**
     * @Route("/getAllReservationservices", name="All_reservationService")
     */
    public function allReservationService(EntityManagerInterface $entityManager, NormalizerInterface $Normalizer)
    {
        $reservationservices = $entityManager
        ->getRepository(Reservationservice::class)
        ->findAll();
        
        $jsonContent = $Normalizer->normalize($reservationservices, 'json', ['groups'=>'post:read']);

        return new Response(json_encode($jsonContent));
    }
  

     /******************Ajouter reservation *******************/

    /**
     * @Route("/addReservationservice", name="add_reservationService")
     * Method("POST")
     */
    public function ajouterReservationService(Request $request,NormalizerInterface $Normalizer) 
    {
        $reservationservice = new Reservationservice();
         // $iduser = $request->get("iduser")->getId();
         // $idservice = $request->get("idservice")->getId();
        $date = $request->get("date");
        $heuredeb = $request->get("heuredeb");
        $heurefin = $request->get("heurefin");
        $prixresserv = $request->get("prixresserv");
        $etat = $request->get("etat");
        $em = $this->getDoctrine()->getManager();
        //$date = new \DateTime('now');

        //  $reservationservice->setIduser($iduser);
        //  $reservationservice->setIdservice($idservice);
        $reservationservice->setDate($date);
        $reservationservice->setHeuredeb($heuredeb);
        $reservationservice->setHeurefin($heurefin);
        $reservationservice->setPrixresserv($prixresserv);
        $reservationservice->setEtat(0);

        $em->persist($reservationservice);
        $em->flush();

          
        $jsonContent = $Normalizer->normalize($reservationservice, 'json', ['groups'=>'post:read']);

        return new Response(json_encode($jsonContent));
       

    }


  

    /********Recuperer reservation selon id *********/

    
    /**
     * @Route("/reservationserviceById/{id}", name="display_reservationService")
     */
    public function ReservationserviceById(Request $request,$id, NormalizerInterface $Normalizer)
    {
        $em = $this->getDoctrine()->getManager();
        $reservationservice = $em->getRepository(Reservationservice::class)->find($id);
        $jsonContent = $Normalizer->normalize($reservationservice,'json', ['groups'=>'post:read']);
        return new Response(json_encode($jsonContent)); 
    }


    /***********Modification ********** */
    /**
     * @Route("/updateReservationService/{id}",name="updateReservationService")
     */
    public function updateReservationService(Request $request,NormalizerInterface $Normalizer, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $reservationservice = $em->getRepository(Reservationservice::class)->find($id);
        
        $date = $request->get("date");
        $heuredeb = $request->get("heuredeb");
        $heurefin = $request->get("heurefin");
        $prixresserv = $request->get("prixresserv");
        $etat = $request->get("etat");
        
        $reservationservice->setDate($date);
        $reservationservice->setHeuredeb($heuredeb);
        $reservationservice->setHeurefin($heurefin);
        $reservationservice->setPrixresserv($prixresserv);
        $reservationservice->setEtat(0);

        $em->flush();

        $jsonContent = $Normalizer->normalize($reservationservice,'json', ['groups'=>'post:read']);
        return new Response("Reservation updated successfully".json_encode($jsonContent)); 

    }


       /**
     * @Route("/deleteReservationService/{id}", name="delete_reservationService")
     */
    public function deleteReservationService(Request $request,$id, NormalizerInterface $Normalizer)
    {
        $em = $this->getDoctrine()->getManager();
        $reservationservice = $em->getRepository(Reservationservice::class)->find($id);
        $em->remove($reservationservice);
        $em->flush();
        $jsonContent = $Normalizer->normalize($reservationservice,'json', ['groups'=>'post:read']);
        return new Response("Reservation deleted successfully".json_encode($jsonContent)); 
    }

}
