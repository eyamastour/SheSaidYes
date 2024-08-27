<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlanningMobileController extends AbstractController
{
    /**
     * @Route("/planning/mobile", name="app_planning_mobile")
     */
    public function index(): Response
    {
        return $this->render('planning_mobile/index.html.twig', [
            'controller_name' => 'PlanningMobileController',
        ]);
    }
}
