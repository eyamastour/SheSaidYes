<?php

namespace App\Controller;

use App\Form\SearchType;
use App\Form\SearchTypeQ;
use App\Services\QrcodeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/default", name="default")
     * @param Request $request
     * @param QrcodeService $qrcodeService
     * @return Response
     */
    public function index(Request $request, QrcodeService $qrcodeService ): Response
    {
$qrCode=null;
        $form=$this->createForm(SearchTypeQ::class,null);
        $form->handleRequest($request);

        if($form->isSubmitted()&&$form->isValid()){
            $data=$form->getData();
            $qrCode=$qrcodeService->qrcode($data['name']);

        }

        return $this->render('default/index.html.twig', [
            'form' => $form->createView(),
            'qrCode' =>$qrCode
        ]);
    }
}