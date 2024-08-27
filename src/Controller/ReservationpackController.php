<?php

namespace App\Controller;

use App\Entity\Reservationpack;
use App\Form\ReservationpackType;
use App\Repository\reservationpackRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use App\Form\SearchType;
use Doctrine\ORM\Mapping\Id;

/**
 * @Route("/reservationpack")
 */
class ReservationpackController extends AbstractController
{
    /**
     * @Route("/", name="app_reservationpack_index", methods={"GET", "POST"})
     */
    public function index(EntityManagerInterface $entityManager, Request $request, reservationpackRepository $reservationpackRepository): Response
    {
        $user = $this->getUser();
        
        $reservationpacks = $entityManager
            ->getRepository(Reservationpack::class)
            ->findAll();
//dd($reservationpacks);
            $packs = $entityManager
            ->getRepository(Reservationpack::class)
            ->findAll();
          //  dd($packs);
           
            // $userpacks = [];
            // foreach($packs as $item){
               
            //     // $totalService =  $item['service']->getPrix() * $item['quantity'];
            //    if($item['idpack']->getId() == $user->getId())
            //    {
            //     $userpacks[]= $item;
            //    }

            //   }
            //   dd($userpacks);
            $formsearchI = $this->createForm(SearchType::class);
        $formsearchI->handleRequest($request);
        if ($formsearchI->isSubmitted()) {
            $prixrespack = $formsearchI->getData();
            $TSearch = $reservationpackRepository->search($prixrespack['prixrespack']);
        
        return $this->render('reservationpack/index.html.twig',
                array("reservationpacks" => $TSearch,"formsearch" => $formsearchI->createView())) ;
        }
        return $this->render("reservationpack/index.html.twig",array('reservationpacks'=>$reservationpacks,"formsearch" => $formsearchI->createView(), 'user'=>$user, 'packs'=>$packs)
           
        );

        
    }


    /**
     * @Route("/new", name="app_reservationpack_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reservationpack = new Reservationpack();
        $form = $this->createForm(ReservationpackType::class, $reservationpack);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reservationpack);
            $entityManager->flush();

            return $this->redirectToRoute('app_reservationpack_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservationpack/new.html.twig', [
            'reservationpack' => $reservationpack,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_reservationpack_show", methods={"GET"})
     */
    public function show(Reservationpack $reservationpack): Response
    {
        return $this->render('reservationpack/show.html.twig', [
            'reservationpack' => $reservationpack,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_reservationpack_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Reservationpack $reservationpack, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservationpackType::class, $reservationpack);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reservationpack_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservationpack/edit.html.twig', [
            'reservationpack' => $reservationpack,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_reservationpack_delete", methods={"POST"})
     */
    public function delete(Request $request, Reservationpack $reservationpack, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservationpack->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reservationpack);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservationpack_index', [], Response::HTTP_SEE_OTHER);
    }

     /**
     * @Route("/searchPrice", name="searchPrice")
     */
    public function searchPrice(Request $request,NormalizerInterface $Normalizer,reservationpackRepository $repository):Response
    {
        $requestString=$request->get('searchValue');
        $reservation = $repository->findByPrice($requestString);
        $jsonContent = $Normalizer->normalize($reservation, 'json',['Groups'=>'Reservationpack:read']);
        $retour =json_encode($jsonContent);
        return new Response($retour);

    }


}
