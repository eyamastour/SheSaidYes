<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PrestBackController extends AbstractController
{
    /**
     * @Route("/prest/back", name="app_prest_back")
     */
    public function index(): Response
    {
        $user=$this->getUser();
        return $this->render('prest_back/index.html.twig', [
            'controller_name' => 'PrestBackController',
            'user'=>$user
        ]);
    }
}
