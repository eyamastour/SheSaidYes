<?php

namespace App\Controller;
use Knp\Snappy\Pdf;
use App\Entity\Reservationpack;
use App\Entity\Reservationservice;
use App\Entity\Services;
use App\Repository\PacksRepository;
use App\Repository\reservationpackRepository;
use App\Repository\ServicesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;

class PanierController extends AbstractController

{
    /**
     * @Route("/panier", name="app_panier")
     */
    public function index(EntityManagerInterface $entityManager, SessionInterface $session, PacksRepository $packsRepository, ServicesRepository $servicerepository): Response
    {
      $user = $this->getUser();
      $panier = $session->get('panier');

      $panierWithData = [];

      foreach($panier as $id => $quantity) {
        $panierWithData[] = [
          'pack' => $packsRepository->find($id),
          // 'service' => $servicerepository->find($id),
          'quantity' => $quantity
        ];
      }
    // dd($panierWithData);
     
    $total = 0;
    foreach($panierWithData as $item){
      $totalItem = $item['pack']->getPrix() * $item['quantity'];
      // $totalService =  $item['service']->getPrix() * $item['quantity'];
      $total = $total + $totalItem;
     
    }

        // $services = $entityManager
        //     ->getRepository(Services::class) 
        //     ->findAll();
        return $this->render('panier/index.html.twig', [
           // 'services' => $services,
            'items' => $panierWithData,
            'total' => $total,
            'user' => $user
            
        ]);

       
    }
/**
     * @Route("panier/addPanier/{id}", name="add_panier")
     */
    public function addPanier($id,EntityManagerInterface $entityManager, SessionInterface $session){
  
      $panier = $session->get('panier', []);
      if(!empty($panier[$id])){ //incrementer le nombre de produit dans le panier sinon on aurait seulement 1
        $panier[$id]++;
      } else {
        $panier[$id] = 1;
      }
      $session->set('panier', $panier);

      return $this->redirectToRoute("app_panier");
    } 

    /**
     * @Route("panier/removefromPanier/{id}", name="removefromquantity_panier")
     */
    public function removequantity($id,EntityManagerInterface $entityManager, SessionInterface $session){
  
      $panier = $session->get('panier', []);

      if(!empty($panier[$id])){ //incrementer le nombre de produit dans le panier sinon on aurait seulement 1
       if($panier[$id] > 1){
        $panier[$id]--;
       }else{
         unset($panier[$id]);
       }
      
      }
      
      $session->set('panier', $panier);

      return $this->redirectToRoute("app_panier");
    } 

    /**
     *@Route("/panier/remove/{id}", name="panier_remove")
     */
    public function remove($id, SessionInterface $session){
      $panier = $session->get('panier', []);

      if(!empty($panier[$id])) {
        unset($panier[$id]);
      } 

      $session->set('panier', $panier);

      return $this->redirectToRoute("app_panier");
    }


      /**
     *@Route("/panier/removeAll", name="panier_removeAll")
     */
    public function removeAll(SessionInterface $session){
   
        $session->set("panier", []); //session clear

      return $this->redirectToRoute("app_panier");
    }

 


      /**
     * @Route("/receipt", name="receipt")
     */
    public function pdfAction(SessionInterface $session,Request $request, Pdf $snappy, reservationpackRepository $reservationpack)
    {
      $items = $session->get('items');
      $total = $session->get('total');
      $rand1 = random_bytes(6);
      $random = bin2hex($rand1);
      
      // $snappy = $this->get("panier/success_url.html.twig");
       $html = $this->renderView("panier/success.html.twig", array (
       
         "title" => "Awesome pdf",
         'items' => $items,
         'total' => $total,
         'random' => $random
       ), true);
       $filename  = "custom_pdf";
       return new Response(
         $snappy->getOutputFromHtml($html),
         200,
         array(
           'content-Type' => 'application/pdf',
           'Content-Disposition' => 'inline; filename="'.$filename.'pdf"'
         )
         );
         $session->set("panier", []); //session clear
        
   
        
      

    }


      /**
     * @Route("/checkout", name="checkout")
     */
    public function checkout($stripeSK,PacksRepository $packsRepository, SessionInterface $session): Response
    {
      $panier = $session->get('panier');

      $panierWithData = [];

      foreach($panier as $id => $quantity) {
        $panierWithData[] = [
          'pack' => $packsRepository->find($id),
          'quantity' => $quantity
        ];
      }

      $panierWithData = [];
      $total = 0;
      foreach($panierWithData as $item){
        $totalItem = $item['pack']->getPrix() * $item['quantity'];
        $total += $totalItem;
      }

  

        \Stripe\Stripe::setApiKey($stripeSK);

        $session = Session::create([
            'line_items' => [[
              'price_data' => [
                'currency' => 'usd',
                'product_data' => [
                  'name' => "pack" ,
                ],
                'unit_amount' => 6500,
              ],
              'quantity' => $quantity,
            ]],
            'mode' => 'payment',
            'success_url' => $this->generateUrl('success_url',[],
            UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' =>$this->generateUrl('cancel_url',[],
            UrlGeneratorInterface::ABSOLUTE_URL) ,
          ]);
        
          return $this->redirect($session->url,303);

        

    }


     /**
     * @Route("/success_url", name="success_url")
     */
    public function successUrl(SessionInterface $session, PacksRepository $packsRepository): Response
    {
      $panier = $session->get('panier');

      $panierWithData = [];

      foreach($panier as $id => $quantity) {
        $panierWithData[] = [
          'pack' => $packsRepository->find($id),
          'quantity' => $quantity
        ];

       
        
      }
     
    $total = 0;
    foreach($panierWithData as $item){
      $totalItem = $item['pack']->getPrix() * $item['quantity'];
      $total += $totalItem;

    $session->set('items',$panierWithData);
    $session->set('total',$total);
    }

    $rand1 = random_bytes(6);
    $random = bin2hex($rand1);

        return $this->render('panier/success.html.twig', [
      
           'items' => $panierWithData,
           'total' => $total,
           'random' => $random
          
       ]);

       $session->set("panier", []); //session clear

       
    }

    
     /**
     * @Route("/cancel_url", name="cancel_url")
     */
    public function cancelUrl(): Response
    {
        return $this->render('payment/cancel.html.twig');
    }

   
}
 