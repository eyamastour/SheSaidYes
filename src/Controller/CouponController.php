<?php

namespace App\Controller;
use App\Entity\Coupon;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotBlank;

 
class CouponController extends AbstractController
{

     ////////////////////////////////////// GET ALL//////////////////////////////////////////////////////
    /**
     * @Route("/coupon/coupons", name="list_coupon")
     */
    public function index(): Response
    {
         $coupons =$this->getDoctrine()->getRepository(Coupon::class)->findAll();
        return $this->render('coupon\liste.html.twig',['coupons'=>$coupons]);
  
    }

     ////////////////////////////////////// AJOUTER //////////////////////////////////////////////////////
    
        /**
     * @Route("/coupon/new", name="ajouter_coupon")
     * Method({"GET","POST"})
     * 
     */
    public function new(Request $request) {
        $coupons = new Coupon();
        $form = $this->createFormBuilder($coupons)
          ->add('code', TextType::class, ['constraints'=> new NotBlank(['message'=>'veuillez remplir ce champ'])])
          ->add('pourcentageReduction', TextType::class, ['constraints'=> new NotBlank(['message'=>'veuillez remplir ce champ'])])
  ->getForm();
          
  
        $form->handleRequest($request);
  
        if($form->isSubmitted() && $form->isValid()) {
            $coupons = $form->getData();
  
          $entityManager = $this->getDoctrine()->getManager();
          $entityManager->persist($coupons);
          $entityManager->flush();
         
          $this->addFlash('info','Ajouté avec succées');
          return $this->redirectToRoute('list_coupon');
        }
        return $this->render('coupon/ajoutCoupon.html.twig',['form' => $form->createView()]);
    }
    

     ////////////////////////////////////// DETAILS //////////////////////////////////////////////////////
        /**
     * @Route("/coupon/{id}", name="details_coupon")
     */
    public function show($id): Response
    {
        $coupons =$this->getDoctrine()->getRepository(Coupon::class)->find($id);
        return $this->render('coupon\detailCoupon.html.twig',['coupons'=>$coupons]);
    }

 ////////////////////////////////////// MODIFIER //////////////////////////////////////////////////////
       /**
     * @Route("/coupon/update/{id}", name="modifier_coupon")
     * Method({"GET","POST"})
     * 
     */
   
    public function Update(Request $request, $id) {
        $coupons = new Coupon();
        $coupons = $this->getDoctrine()->getRepository(Coupon::class)->find($id);
  
        $form = $this->createFormBuilder($coupons)
        ->add('code', TextType::class)
        ->add('pourcentageReduction', TextType::class)
->getForm();
  
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
  
          $entityManager = $this->getDoctrine()->getManager();
          $entityManager->flush();
          $this->addFlash('info','Modifié avec succées');
          return $this->redirectToRoute('list_coupon');
        }
  
        return $this->render('coupon/modifierCoupon.html.twig', ['form' => $form->createView()]);
      }
     ////////////////////////////////////// SUPPRIMER //////////////////////////////////////////////////////
      /**
     * @Route("/coupon/delete/{id}", name="supprimer_coupon")
     * Method({"DELETE"})
     * 
     */
   
    public function delete(Request $request, $id) {
        $coupons = $this->getDoctrine()->getRepository(Coupon::class)->find($id);
  
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($coupons);
        $entityManager->flush();
  
        $response = new Response();
        $response->send();
        $this->addFlash('info','Supprimé avec succées');
        return $this->redirectToRoute('list_coupon');
      }
    }