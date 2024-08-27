<?php

namespace App\Controller;

use App\Data\Search;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Abonnement;
use App\Entity\Espaceprestatairee;
use App\Form\SearchFormType;
use App\Repository\AbonnementRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class IndexController extends AbstractController
{

  ////////////////////////////////////// GET ALL//////////////////////////////////////////////////////
  /**
   * @Route("/index/get", name="list_abonnes")
   */
  public function index(Request $request , AbonnementRepository $repo): Response
  {
    $data = new Search();
    $form = $this->createForm(SearchFormType::class, $data);
    $form->handleRequest($request);

    $abonnement= $repo->findSearch($data);
   

  
    return $this->render('abonnements\index.html.twig', ['abonnements' => $abonnement,'form'=>$form->createView()]);
  }

  ////////////////////////////////////// AJOUTER //////////////////////////////////////////////////////

  /**
   * @Route("/index/new", name="ajouter")
   * Method({"GET","POST"})
   * 
   */
  public function new(Request $request)
  {
    $abonnement = new Abonnement();
    $abonnement->setDateDebut(new \Datetime('now'));
    $abonnement->setDateFin(new \Datetime('now'));
    $form = $this->createFormBuilder($abonnement)
      ->add('duree', TextType::class)
      ->add('prix', ChoiceType::class, array('choices' => array('500DT' => '500DT', '1000DT' => '1000DT', '2000DT' => '2000DT', '3000DT' => '3000DT')))
      ->add('idprest', EntityType::class, [
        'class' => Espaceprestatairee::class

      ])
->getForm();


    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $abonnement = $form->getData();

      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($abonnement);
      $entityManager->flush();

       $this->addFlash('info','Ajouté avec succées');
      return $this->redirectToRoute('list_abonnes');
    }
    return $this->render('abonnements/ajout.html.twig', ['form' => $form->createView()]);
  }


  ////////////////////////////////////// DETAILS //////////////////////////////////////////////////////
  /**
   * @Route("/abonne/{id}", name="details_abonnees")
   */
  public function show($id): Response
  {
    $abonnement = $this->getDoctrine()->getRepository(Abonnement::class)->find($id);
    return $this->render('abonnements\detail.html.twig', ['abonnement' =>  $abonnement]);
  }

  ////////////////////////////////////// MODIFIER //////////////////////////////////////////////////////
  /**
   * @Route("/abonne/update/{id}", name="modifier")
   * Method({"GET","POST"})
   * 
   */

  public function Update(Request $request, $id)
  {
    $abonnement = new Abonnement();
    $abonnement = $this->getDoctrine()->getRepository(Abonnement::class)->find($id);

    $form = $this->createFormBuilder($abonnement)
      ->add('duree', TextType::class)
      ->add('prix', TextType::class)
      ->add('dateDebut', DateType::class)
      ->add('dateFin', DateType::class)
      ->add(
        'save',
        SubmitType::class,
        array(
          'label' => 'Modifier'
        )
      )->getForm();

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {

      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->flush();
      $this->addFlash('info','Modifié avec succées');
      return $this->redirectToRoute('list_abonnes');
    }

    return $this->render('abonnements/update.html.twig', ['form' => $form->createView()]);
  }
  ////////////////////////////////////// SUPPRIMER //////////////////////////////////////////////////////
  /**
   * @Route("/abonne/delete/{id}", name="supprimer")
   * Method({"DELETE"})
   * 
   */

  public function delete(Request $request, $id)
  {
    $abonnement = $this->getDoctrine()->getRepository(Abonnement::class)->find($id);

    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->remove($abonnement);
    $entityManager->flush();

    $response = new Response();
    $response->send();
    $this->addFlash('info','Supprimé avec succées');
    return $this->redirectToRoute('list_abonnes');
  }
  /**
   * @Route("/listpdf", name="listpdf")
   */
  public function Listpdf(AbonnementRepository $abonnementRepository)
  {
    // Configure Dompdf according to your needs
    $pdfOptions = new Options();
    $pdfOptions->set('defaultFont', 'Arial');
    $pdfOptions->set('isHtml5ParserEnabled', true);
    $pdfOptions->set('isRemoteEnabled', true);

    // Instantiate Dompdf with our options
    $dompdf = new Dompdf($pdfOptions);
    $abonnement = $abonnementRepository->findAll();

    // Retrieve the HTML generated in our twig file
    $html = $this->renderView('abonnements\pdf.html.twig', ['abonnements' => $abonnement]);


    // Load HTML to Dompdf
    $dompdf->loadHtml($html);

    // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
    $dompdf->setPaper('A4', 'portrait');

    // Render the HTML as PDF
    $dompdf->render();

    // Output the generated PDF to Browser (force download)
    $dompdf->stream("AbonnementListe.pdf", [
      "Attachment" => true
    ]);
  }

  /////////////////////////Recherche///////////////////////////////////
  /**
   * @Route("/recherche", name="recherche")
   */
  public function Recherche(Request $request)
  {
    $em = $this->getDoctrine()->getManager();
    $abonnement = $em->getRepository(Abonnement::class)->findAll();
    if ($request->isMethod("POST")) {
      $prix = $request->get('prix');
      $abonnement = $em->getRepository(Abonnement::class)->findBy(array('prix' => $prix));
    }
    return $this->render('abonnements\index.html.twig', ['abonnements' => $abonnement]);
  }

      /**
     * @Route("/tri", name="tri")
     */

    public function Tri(Request $request)
    {
      $data = new Search();
      $form = $this->createForm(SearchFormType::class, $data);
      $form->handleRequest($request);
  
    //  $abonnement= $repo->findSearch($data);
        $em = $this->getDoctrine()->getManager();

        $query = $em->createQuery(
            'SELECT a FROM App\Entity\Abonnement a
            ORDER BY a.dateDebut DESC' 
        );
            
    
        $rep = $query->getResult(); 

        return $this->render('abonnements/index.html.twig', 
        array('abonnements' => $rep ,'form'=>$form->createView()));
    
    } 
}
