<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MapController extends AbstractController
{
    /**
     * @Route("/map", name="app_map")
     */
    public function index(): Response
    {
        return $this->render('map/indexx.html.twig', [
            'controller_name' => 'MapController',
        ]);
    }
}
