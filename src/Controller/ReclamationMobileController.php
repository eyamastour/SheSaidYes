<?php

namespace App\Controller;

use App\Entity\Reclamation;
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



class ReclamationMobileController extends AbstractController
{


    /******************Ajouter reclamation *******************/

    /**
     * @Route("/addReclamationMobile", name="add_reclamationMobile")
     * Method("POST")
     */
    public function ajouterReclamationMobile(Request $request, NormalizerInterface $Normalizer)
    {
        $reclamation = new Reclamation();
        $datereclamtion = $request->get("datereclamtion");
        $descriptionreclamtion = $request->get("descriptionreclamtion");
        $imagereclamtion = $request->get("imagereclamtion");
        $etatreclamtion = $request->get("etatreclamtion");
        $em = $this->getDoctrine()->getManager();

        $reclamation->setDatereclamtion($datereclamtion);
        $reclamation->setDescriptionreclamtion($descriptionreclamtion);
        $reclamation->setImagereclamtion($imagereclamtion);
        $reclamation->setEtatreclamtion("Non traitée");
        

        $em->persist($reclamation);
        $em->flush();


        $jsonContent = $Normalizer->normalize($reclamation, 'json', ['groups' => 'post:read']);

        return new Response(json_encode($jsonContent));
    }


    /******** Affichage reservationService *************/

    /**
     * @Route("/AllReclamationMobile", name="display_reclamationMobiles")
     */
    public function allReservationService(EntityManagerInterface $entityManager, NormalizerInterface $Normalizer)
    {
        $reclamation = $entityManager
            ->getRepository(Reclamation::class)
            ->findAll();

        $jsonContent = $Normalizer->normalize($reclamation, 'json', ['groups' => 'post:read']);

        return new Response(json_encode($jsonContent));
    }

    /********Recuperer reclamation selon id *********/


    /**
     * @Route("/reclamationMobileById/{idReclamation}", name="display_reclamationMobile")
     */
    public function ReclamationMobileById(Request $request, $id, NormalizerInterface $Normalizer)
    {
        $em = $this->getDoctrine()->getManager();
        $reclamation = $em->getRepository(Reclamation::class)->find($id);
        $jsonContent = $Normalizer->normalize($reclamation, 'json', ['groups' => 'post:read']);
        return new Response(json_encode($jsonContent));
    }


    /***********Modification ********** */
    /**
     * @Route("/updateReclamationMobile/{idReclamation}",name="updateReclamationMobile")
     */
    public function updateReclamationMobile(Request $request, NormalizerInterface $Normalizer, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $reclamation = $em->getRepository(Reclamation::class)->find($id);

        $reclamation = new Reclamation();
        $datereclamtion = $request->get("datereclamtion");
        $descriptionreclamtion = $request->get("descriptionreclamtion");
        $imagereclamtion = $request->get("imagereclamtion");
        $etatreclamtion = $request->get("etatreclamtion");

        $reclamation->setDatereclamtion($datereclamtion);
        $reclamation->setDescriptionreclamtion($descriptionreclamtion);
        $reclamation->setImagereclamtion($imagereclamtion);
        $reclamation->setEtatreclamtion("Non traitée");

        $em->flush();

        $jsonContent = $Normalizer->normalize($reclamation, 'json', ['groups' => 'post:read']);
        return new Response("Reclamation updated successfully" . json_encode($jsonContent));
    }


    /**
     * @Route("/deleteReclamationMobile/{idReclamation}", name="delete_ReclamationMobile")
     */
    public function deleteReclamationMobile(Request $request, $id, NormalizerInterface $Normalizer)
    {
        $em = $this->getDoctrine()->getManager();
        $reclamation = $em->getRepository(Reclamation::class)->find($id);
        $em->remove($reclamation);
        $em->flush();
        $jsonContent = $Normalizer->normalize($reclamation, 'json', ['groups' => 'post:read']);
        return new Response("Reclamation deleted successfully" . json_encode($jsonContent));
    }
}
