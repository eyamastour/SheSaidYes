<?php

namespace App\Controller;

use App\Entity\Espaceprestatairee;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Doctrine\ORM\EntityManagerInterface;

class EspaceprestataireMobileController extends AbstractController
{
    /**
     * @Route("/espaceprestataire/mobile", name="app_espaceprestataire_mobile")
     */
    public function index(): Response
    {
        return $this->render('espaceprestataire_mobile/index.html.twig', [
            'controller_name' => 'EspaceprestataireMobileController',
        ]);
    }
      /******************Ajouter espace prestataire *******************/

    /**
     * @Route("/addEspace", name="add_Espace")
     * Method("POST")
     */
    public function ajouterEspaceprestataire(Request $request,NormalizerInterface $Normalizer) 
    {
        $espaceprestaire = new Espaceprestatairee();
         // $iduser = $request->get("iduser")->getId();
         // $idservice = $request->get("idservice")->getId();
        $nom = $request->get("nomservice");
        $num = $request->get("numsociete");
        $fax = $request->get("faxsociete");
        $cat = $request->get("catsociete");
        $logo = $request->get("logo");
        $url = $request->get("url");
        $type = $request->get("typesociete");

        $em = $this->getDoctrine()->getManager();
        //$date = new \DateTime('now');

        //  $reservationservice->setIduser($iduser);
        //  $reservationservice->setIdservice($idservice);
        $espaceprestaire->setNomsociete($nom);
        $espaceprestaire->setNumsociete($num);
        $espaceprestaire->setFaxsociete($fax);
        $espaceprestaire->setCatsociete($cat);
        $espaceprestaire->setTypesociete($logo);
        $espaceprestaire->setLogo($url);
        $espaceprestaire->setUrl($type);

        $em->persist($espaceprestaire);
        $em->flush();

          
        $jsonContent = $Normalizer->normalize($espaceprestaire, 'json', ['groups'=>'post:read']);

        return new Response(json_encode($jsonContent));
       

    }
 /******** Affichage des espaces*************/

    /**
     * @Route("/AllEspace", name="display_Espace")
     */
    public function allEspace(EntityManagerInterface $entityManager, NormalizerInterface $Normalizer)
    {
        $espaceprestatairesservices = $entityManager
        ->getRepository(espaceprestataireRepository::class)
        ->findAll();
        
        $jsonContent = $Normalizer->normalize($espaceprestatairesservices, 'json', ['groups'=>'post:read']);

        return new Response(json_encode($jsonContent));
    }
  /********Recuperer espace selon id *********/

    
    /**
     * @Route("/EspaceById/{id}", name="display_espaceService")
     */
    public function ReservationserviceById(Request $request,$id, NormalizerInterface $Normalizer)
    {
        $em = $this->getDoctrine()->getManager();
        $espaceservice = $em->getRepository(espaceprestataireRepository::class)->find($id);
        $jsonContent = $Normalizer->normalize($espaceservice,'json', ['groups'=>'post:read']);
        return new Response(json_encode($jsonContent)); 
    }
    public function updateReservationService(Request $request,NormalizerInterface $Normalizer, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $espaceprestaire = $em->getRepository(espaceprestataireRepository::class)->find($id);
        
        
        $nom = $request->get("nomservice");
        $num = $request->get("numsociete");
        $fax = $request->get("faxsociete");
        $cat = $request->get("catsociete");
        $logo = $request->get("logo");
        $url = $request->get("url");
        $type = $request->get("typesociete");
        
        $espaceprestaire->setNomsociete($nom);
        $espaceprestaire->setNumsociete($num);
        $espaceprestaire->setFaxsociete($fax);
        $espaceprestaire->setCatsociete($cat);
        $espaceprestaire->setTypesociete($logo);
        $espaceprestaire->setLogo($url);
        $espaceprestaire->setUrl($type);

        $em->flush();

        $jsonContent = $Normalizer->normalize($espaceprestaire,'json', ['groups'=>'post:read']);
        return new Response("espaceprestataire updated successfully".json_encode($jsonContent)); 

    }
       /**
     * @Route("/deleteEspacePrestataire/{id}", name="delete_Espace")
     */
    public function deleteEspacePrestataire(Request $request,$id, NormalizerInterface $Normalizer)
    {
        $em = $this->getDoctrine()->getManager();
        $espaceprestaire = $em->getRepository(espaceprestataireRepository::class)->find($id);
        $em->remove($espaceprestaire);
        $em->flush();
        $jsonContent = $Normalizer->normalize($espaceprestaire,'json', ['groups'=>'post:read']);
        return new Response("espaceprestataire deleted successfully".json_encode($jsonContent)); 
    }






}
