<?php

namespace App\Controller;

use App\Entity\Planning;
use App\Entity\Services;
use App\Form\Planning1Type;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\planningRepository;

/**
 * @Route("/plannning")
 */
class PlannningController extends AbstractController
{
    /**
     * @Route("/", name="app_plannning_index")
     */
    public function index(EntityManagerInterface $entityManager,planningRepository $planning): Response
    {
        
        

        return $this->render('plannning/index.html.twig', [
            'plannings' =>$planning
            ->findBy([],["dateplan" => "asc"])
        ]);
    }

    /**
     * @Route("/new", name="app_plannning_new")
     */
    public function new(Request $request, EntityManagerInterface $entityManager,planningRepository $planningRepository): Response
    {
        $planning = new Planning();
        $form = $this->createForm(Planning1Type::class, $planning);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $planning=$form->getData();
            
            $entityManager->persist($planning);
            $entityManager->flush();

            return $this->redirectToRoute('app_plannning_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('plannning/new.html.twig', [
            'planning' => $planning,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idplan}", name="app_plannning_show")
     */
    public function show(Planning $planning): Response
    {
        return $this->render('plannning/show.html.twig', [
            'planning' => $planning,
        ]);
    }

    /**
     * @Route("/{idplan}/edit", name="app_plannning_edit")
     */
    public function edit(Request $request, Planning $planning, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Planning1Type::class, $planning);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_plannning_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('plannning/edit.html.twig', [
            'planning' => $planning,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idplan}", name="app_plannning_delete")
     */
    public function delete(Request $request, Planning $planning, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$planning->getIdplan(), $request->request->get('_token'))) {
            $entityManager->remove($planning);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_plannning_index', [], Response::HTTP_SEE_OTHER);
    }
    



    /**
     * @Route("/listjoin/{idplan}",name="listactualitesf1")
     */
    
    public function listservice($idplan,planningRepository $planningRepository)
    {
        $serv=$this->getDoctrine()->getRepository(Services::class)->find($idplan);
        $act=$planningRepository->getByService($serv);
        $C=$this->getDoctrine()->getRepository(Services::class)->findAll();
        return $this->render("plannning/index.html.twig",array('Planning'=>$act,'Services'=>$C));

    }
}