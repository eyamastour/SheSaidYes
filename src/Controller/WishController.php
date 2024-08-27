<?php

namespace App\Controller;

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

class WishController extends AbstractController
{
    /**
     * @Route("/wish", name="app_wish")
     */
    public function index(EntityManagerInterface $entityManager, SessionInterface $session, PacksRepository $packsRepository, ServicesRepository $servicesRepository): Response
    {
      $wish = $session->get('wish');

      $wishWithData = [];

      foreach($wish as $id => $quantity) {
        $wishWithData[] = [
          'service' => $servicesRepository->find($id),
          'pack' => $packsRepository->find($id),
          'quantity' => $quantity
        ];
      }
   
     
    $total = 0;
    foreach($wishWithData as $item){
      $totalItem = $item['service']->getPrix() * $item['quantity'];
      $totalItem = $item['pack']->getPrix() * $item['quantity'];
      $total += $totalItem;
    }


        $services = $entityManager
            ->getRepository(Services::class) 
            ->findAll();
        return $this->render('wish/index.html.twig', [
           'services' => $services,
            'items' => $wishWithData,
            'total' => $total
        ]);

       
    }
    /**
     * @Route("wish/addWish/{id}", name="add_wish")
     */
    public function addWish($id,EntityManagerInterface $entityManager, SessionInterface $session){
  
      $wish = $session->get('wish', []);
      if(!empty($wish[$id])){ //incrementer le nombre de produit dans le panier sinon on aurait seulement 1
        $wish[$id]++;
      } else {
        $wish[$id] = 1;
      }
      $session->set('wish', $wish);

      return $this->redirectToRoute("app_wish");
    } 

    /**
     * @Route("wish/removefromWish/{id}", name="removefromquantity_wish")
     */
    public function removequantity($id,EntityManagerInterface $entityManager, SessionInterface $session){
  
      $wish = $session->get('wish', []);

      if(!empty($wish[$id])){ //incrementer le nombre de produit dans 
       if($wish[$id] > 1){
        $wish[$id]--;
       }else{
         unset($wish[$id]);
       }
      
      }
      
      $session->set('wish', $wish);

      return $this->redirectToRoute("app_wish");
    } 

    /**
     *@Route("/wish/remove/{id}", name="wish_remove")
     */
    public function remove($id, SessionInterface $session){
      $wish = $session->get('wish', []);

      if(!empty($wish[$id])) {
        unset($wish[$id]);
      } 

      $session->set('wish', $wish);

      return $this->redirectToRoute("app_wish");
    }


      /**
     *@Route("/wish/removeAll", name="wish_removeAll")
     */
    public function removeAll(SessionInterface $session){
   
        $session->set("wish", []); //session clear

      return $this->redirectToRoute("app_wish");
    }

    
}
 