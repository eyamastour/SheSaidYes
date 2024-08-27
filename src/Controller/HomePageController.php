<?php

namespace App\Controller;

use App\Repository\PacksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomePageController extends AbstractController
{


  /**
   * @Route("/home", name="app_home_page")
   */
  public function index(): Response
  {
    //  if ($this->isGranted('ROLE_USER') == false) {
    //   return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
    // }
    //dd($this->getUser());
    $user=$this->getUser();
    return $this->render('home_page/index.html.twig',
  [
    'user'=>$user
  ]);
  }


  /**
   * @Route("/front", name="app_front")
   */
  public function dropdownpanier(SessionInterface $session, PacksRepository $packsRepository): Response
  {
    $panier = $session->get('panier');

    $panierWithData = [];

    foreach ($panier as $id => $quantity) {
      $panierWithData[] = [
        'pack' => $packsRepository->find($id),
        'quantity' => $quantity
      ];
    }

    $total = 0;
    foreach ($panierWithData as $item) {
      $totalItem = $item['pack']->getPrix() * $item['quantity'];
      $total += $totalItem;
    }

    return $this->render('front_base.html.twig', [
      'items' => $panierWithData,
      'total' => $total
    ]);
  }
}
