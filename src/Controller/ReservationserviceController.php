<?php

namespace App\Controller;

use App\Entity\PropertySearch1;
use App\Entity\Reservationpack;
use App\Entity\Reservationservice;
use App\Form\PropertySearchType1Type;
use App\Form\ReservationserviceType;
use App\Repository\reservationserviceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @Route("/reservationservice")
 */
class ReservationserviceController extends AbstractController
{
   




    /**
     * @Route("/", name="app_reservationservice_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        /* $search = new PropertySearch1();
        $form = $this->createForm(PropertySearchType1Type::class, $search);
        $form->handleRequest($request);
        $reservation = [];
      
        if($form->isSubmitted() && $form->isValid()) {
            $maxPrice = $search->getMaxPrice();
            if($maxPrice!=""){
                $reservation= $this->getDoctrine()->getRepository(Reservationservice :: class)-> findBy(['prixresserv'=> $maxPrice]);
            }
                else 
             $reservation = $this->getDoctrine()->getRepository(Reservationpack :: class)->findAll();
            
            } */
        
            $user = $this->getUser();

            $services = $entityManager
            ->getRepository(Reservationservice::class)
            ->findByUser($user->getId());
           // dd($services);

        $reservationservices = $entityManager
            ->getRepository(Reservationservice::class)
            ->findAll();

        return $this->render('reservationservice/index.html.twig', [
            'reservationservices' => $reservationservices,
            'user'=>$user, 
            'services'=>$services 
           
        ]);
    }

    /**
     * @Route("/new", name="app_reservationservice_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reservationservice = new Reservationservice();
        $form = $this->createForm(ReservationserviceType::class, $reservationservice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reservationservice);
            $entityManager->flush();

            return $this->redirectToRoute('app_reservationservice_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservationservice/new.html.twig', [
            'reservationservice' => $reservationservice,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_reservationservice_show", methods={"GET"})
     */
    public function show(Reservationservice $reservationservice): Response
    {
        return $this->render('reservationservice/show.html.twig', [
            'reservationservice' => $reservationservice,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_reservationservice_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Reservationservice $reservationservice, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservationserviceType::class, $reservationservice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reservationservice_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservationservice/edit.html.twig', [
            'reservationservice' => $reservationservice,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_reservationservice_delete", methods={"POST"})
     */
    public function delete(Request $request, Reservationservice $reservationservice, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservationservice->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reservationservice);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservationservice_index', [], Response::HTTP_SEE_OTHER);
    }


    /**
     * @Route("/s/search", name="search")
     */
    public function search(Request $request,NormalizerInterface $Normalizer,reservationserviceRepository $repository):Response
    {
        $requestString=$request->get('searchValue');
        $Reservationservice = $repository->findByDate($requestString);
        $jsonContent = $Normalizer->normalize($Reservationservice, 'json',['Groups'=>'Reservationservice:read']);
        $retour =json_encode($jsonContent);
        return new Response($retour);

    }



  
}
