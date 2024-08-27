<?php

namespace App\Controller;

use DateInterval;
use App\Entity\Coupon;
use App\Entity\Abonnement;
use App\Form\ClientAbonneType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class AbonneClientController extends AbstractController
{

    /**
     * @Route("/", name="app_home")
     */
    public function index(): Response
    {
        return $this->render('abonne_client/main.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
    /**
     * @Route("/abonne/client", name="app_abonne_client")
     */
    public function client(Request $request,\Swift_Mailer $mailer): Response
    {

        $abonne = new Abonnement();
        
        $form = $this->createForm(ClientAbonneType::class, $abonne);
        $form->handleRequest($request);
        
        $entityManager = $this->getDoctrine()->getManager();
        if ($form->isSubmitted() ) {
           // dd($form->get('duree')->getData());
            //send email
            $message = (new \Swift_Message('Abonnement Expiration')) //subject
                ->setFrom('anis.ammar@esprit.tn')
                ->setTo('anis.ammar@esprit.tn')
                ;



            $time = new \DateTime('now');
            
            $abonne->setDateDebut(new \DateTime());
            if($form->get('duree')->getData() == "1 mois")
        {
                $abonne->setPrix("500 Dt");
                
                $abonne->setDateFin(new \DateTime('+1 month'));
                $date=new \DateTime('+1 month');
                $date->format('Y-m-d H:i:s');

                $message ->setBody("Votre abonnement expire dans 1 mois. pour renouveller votre abonnement merci d'acceder a l'url : http://127.0.0.1:8000/abonne/client , date expiration : ". $date->format('Y-m-d H:i:s')
                  );
                
                
                $entityManager->persist($abonne);
                $entityManager->flush();

                
                 $mailer->send($message);
                 return $this->redirectToRoute('paiement',['prix'=> " 500 " ,"pourcentage" =>" "]);
        }

        if($form->get('duree')->getData() == "3 mois")
        {
                $abonne->setPrix("1000 Dt");
                
                $abonne->setDateFin(new \DateTime('+3 month'));
                
                $date=new \DateTime('+3 month');
                $date->format('Y-m-d H:i:s');

                $message ->setBody("Votre abonnement expire dans 3 mois. pour renouveller votre abonnement merci d'acceder a l'url : http://127.0.0.1:8000/abonne/client , date expiration : ". $date->format('Y-m-d H:i:s')
                  );
                
                
                $entityManager->persist($abonne);
                $entityManager->flush();

                
                 $mailer->send($message);
                 return $this->redirectToRoute('paiement',['prix'=> " 1000 " ,"pourcentage" =>" "]);
        }

        if($form->get('duree')->getData() == "6 mois")
        {
                $abonne->setPrix("2000 Dt");
                
                
             $abonne->setDateFin(new \DateTime('+6 month'));
             $date=new \DateTime('+6 month');
             $date->format('Y-m-d H:i:s');

             $message ->setBody("Votre abonnement expire dans 6 mois. pour renouveller votre abonnement merci d'acceder a l'url : http://127.0.0.1:8000/abonne/client , date expiration : ". $date->format('Y-m-d H:i:s')
               );
             
             
             $entityManager->persist($abonne);
             $entityManager->flush();

             
              $mailer->send($message);
                 return $this->redirectToRoute('paiement',['prix'=> " 2000 " ,"pourcentage" =>" "]);
        }
     
        if($form->get('duree')->getData() == "12 mois")
        {
                $abonne->setPrix("3000 Dt");
                
            $abonne->setDateFin(new \DateTime('+12 month'));
                
            $date=new \DateTime('+12 month');
            $date->format('Y-m-d H:i:s');

            $message ->setBody("Votre abonnement expire dans 12 mois. pour renouveller votre abonnement merci d'acceder a l'url : http://127.0.0.1:8000/abonne/client , date expiration : ". $date->format('Y-m-d H:i:s')
              );
            
            
            $entityManager->persist($abonne);
            $entityManager->flush();

            
             $mailer->send($message);
                 return $this->redirectToRoute('paiement',['prix'=> " 3000 " ,"pourcentage" =>" "]);
        }
                   
                 




                
                }
                return $this->render('abonne_client/index.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
        }
