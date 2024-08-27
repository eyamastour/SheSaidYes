<?php

namespace App\Controller;

use App\Repository\AbonnementRepository;
use App\Repository\CouponRepository;
use Stripe\Stripe;

use Stripe\Checkout\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class PaiementController extends AbstractController
{

    /**
     * @Route("/paiement/{prix}/{pourcentage}", name="paiement")
     */
    public function index(CouponRepository $repo,Request $request,$prix , AbonnementRepository $abonnementRepository,$pourcentage): Response
    {

      //dd($abonnement);
      $form = $this->createFormBuilder()
      ->add('code', TextType::class)
      ->getForm();
      

     $form->handleRequest($request);

     $coupons = $repo->findAll();
    

     if($form->isSubmitted())
     {

      $code = $form->get('code')->getData();

      $coupon= $repo->findOneBy(array('code'=>$code));
      $total = 0;

      if($coupon){
        $this->addFlash('info','coupon valide');
         
       $pourcentage=$coupon->getPourcentageReduction();
      
      // dd((int)$pourcentage);
       
      $total = (int) $prix - ((int) $prix * ((int)$pourcentage /100 )); 


       return $this->redirectToRoute('paiement',['prix'=>$prix ,'pourcentage'=>$pourcentage]);


      }
      else {
        $this->addFlash('non','coupon  non valide');
      }

      //$total = (int) $prix - ((int) $prix * ((int)$pourcentage /100 )); 
     // dd($pourcentage);


     }
     
     $total = (int) $prix - ((int) $prix * ((int)$pourcentage /100 )); 

        return $this->render('paiement/index.html.twig', [
            'controller_name' => 'PaiementController',
            'form'=>$form->createView(),
            'prix'=>$prix,
            'pourcentage'=>$pourcentage,
            'total'=>$total
            
        ]);
    }


    /**
     * @Route("/checkout", name="checkout_stripe")
     */
    public function checkout($stripeSK): Response
    {

       
        \Stripe\Stripe::setApiKey($stripeSK);

        $session = Session::create([
            'line_items' => [[
              'price_data' => [
                'currency' => 'usd',
                'product_data' => [
                  'name' => "Abonnement" ,
                ],
                'unit_amount' => 500.00,
              ],
              'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url'          => $this->generateUrl('success_url_a', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url'           => $this->generateUrl('cancel_url_a', [], UrlGeneratorInterface::ABSOLUTE_URL),
          ]);
        
          return $this->redirect($session->url,303);
    }




    /**
     * @Route("/success-url", name="success_url_a")
     */
    public function successUrl(): Response
    {
        return $this->render('paiement/succes.html.twig', []);
    }


    /**
     * @Route("/cancel-url", name="cancel_url_a")
     */
    public function cancelUrl(): Response
    {
        return $this->redirectToRoute('app_abonne_client');
    }
}
