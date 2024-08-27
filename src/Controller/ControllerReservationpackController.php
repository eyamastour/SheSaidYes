<?php

namespace App\Controller;

use App\Entity\Packs;
use App\Entity\Reservationpack;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ControllerReservationpackController extends AbstractController
{
    /******************Ajouter reservation *******************/

    /**
     * @Route("/addReservationpack", name="add_reservationpack")
     * Method("POST")
     */
    public function ajouterReservationPack(Request $request,NormalizerInterface $Normalizer) 
    {
        $reservationpack = new Reservationpack();
        $iduser = $request->get(Utilisateur::class)->getId();
        $idpack = $request->get(Packs::class)->getId();
        $date = $request->get("date");
        $heuredeb = $request->get("heuredeb");
        $heurefin = $request->get("heurefin");
        $prixrespack = $request->get("prixrespack");
        $etat = $request->get("etat");
        $em = $this->getDoctrine()->getManager();
        //$date = new \DateTime('now');

        $reservationpack->setIduser($iduser);
        $reservationpack->setIdpack($idpack);
        $reservationpack->setDate($date);
        $reservationpack->setHeuredeb($heuredeb);
        $reservationpack->setHeurefin($heurefin);
        $reservationpack->setPrixrespack($prixrespack);
        $reservationpack->setEtat(0);

        $em->persist($reservationpack);
        $em->flush();

          
        $jsonContent = $Normalizer->normalize($reservationpack, 'json', ['groups'=>'post:read']);

        return new Response(json_encode($jsonContent));
       

    }


    /******** Affichage reservationpack *************/

    /**
     * @Route("/displayreservationpack", name="display_reservationpack")
     */
    public function Allreservationpack(EntityManagerInterface $entityManager, NormalizerInterface $Normalizer)
    {
        $reservationpack = $entityManager
        ->getRepository(Reservationpack::class)
        ->findAll();
        
        $jsonContent = $Normalizer->normalize($reservationpack, 'json', ['groups'=>'post:read']);

        return new Response(json_encode($jsonContent));
    }

    /********Recuperer reservation selon id *********/

    
    /**
     * @Route("/reservationpackById/{id}", name="display_reservationPack")
     */
    public function ReservationpackById(Request $request,$id, NormalizerInterface $Normalizer)
    {
        $em = $this->getDoctrine()->getManager();
        $reservationpack = $em->getRepository(Reservationpack::class)->find($id);
        $jsonContent = $Normalizer->normalize($reservationpack,'json', ['groups'=>'post:read']);
        return new Response(json_encode($jsonContent)); 
    }


    /***********Modification ********** */
    /**
     * @Route("/updateReservationPack/{id}",name="updateReservationPack")
     */
    public function updateReservationPack(Request $request,NormalizerInterface $Normalizer, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $reservationpack = $em->getRepository(Reservationpack::class)->find($id);
        
        $date = $request->get("date");
        $heuredeb = $request->get("heuredeb");
        $heurefin = $request->get("heurefin");
        $prixrespack = $request->get("prixrespack");
        $etat = $request->get("etat");
        
        $reservationpack->setDate($date);
        $reservationpack->setHeuredeb($heuredeb);
        $reservationpack->setHeurefin($heurefin);
        $reservationpack->setPrixresserv($prixrespack);
        $reservationpack->setEtat(0);

        $em->flush();

        $jsonContent = $Normalizer->normalize($reservationpack,'json', ['groups'=>'post:read']);
        return new Response("Reservation updated successfully".json_encode($jsonContent)); 

    }


       /**
     * @Route("/deleteReservationPack/{id}", name="delete_reservationPack")
     */
    public function deleteReservationPack(Request $request,$id, NormalizerInterface $Normalizer)
    {
        $em = $this->getDoctrine()->getManager();
        $reservationpack = $em->getRepository(Reservationpack::class)->find($id);
        $em->remove($reservationpack);
        $em->flush();
        $jsonContent = $Normalizer->normalize($reservationpack,'json', ['groups'=>'post:read']);
        return new Response("Reservation deleted successfully".json_encode($jsonContent)); 
    }

}
