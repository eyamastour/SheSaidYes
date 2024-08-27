<?php

namespace App\Controller;
use App\Entity\Espaceprestatairee;
use App\Form\Espaceprestatairee1Type;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\espaceprestataireRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use Twilio\Rest\Client;

/**
 * @Route("/espaceprestataire")
 */
class EspaceprestataireController extends AbstractController
{
    
    /**
     * @Route("/", name="app_espaceprestataire_index", methods={"GET"})
     */
    public function index(espaceprestataireRepository $espaceprestataireRepository): Response
    {
        return $this->render('espaceprestataire/index.html.twig', [
            'espaceprestataire' => $espaceprestataireRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_espaceprestataire_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $espaceprestatairee = new Espaceprestatairee();
        $form = $this->createForm(Espaceprestatairee1Type::class, $espaceprestatairee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $request->files->get('espaceprestatairee1')['logo'];
            $uploads_directory = $this->getParameter('uploads_directory');
            $filename = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move(
                $uploads_directory,
                $filename
            );
            $espaceprestatairee->setLogo($filename);
            $entityManager->persist($espaceprestatairee);
            $entityManager->flush();
            $num=$form['numsociete']->getData(); //num du formulaire
            $sid = 'AC2fe994f9b7541489c301ee0abaa8d2c1';
            $token = '409cb7ab3324d9114663403c45393004';
            $sms = new \Twilio\Rest\Client($sid, $token);
            $sms->messages->create(
                '+21655396763', // Send text to this number
                [
                    'from' => '+17473000367',// My Twilio phone number
                    'body' => 'Vous etes le bienvenue '

                ]
            );
            return $this->redirectToRoute('app_espaceprestataire_index', [], Response::HTTP_SEE_OTHER);
        }
       
        return $this->render('espaceprestataire/new.html.twig', [
            'espaceprestatairee' => $espaceprestatairee,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idprest}", name="app_espaceprestataire_show", methods={"GET"})
     */
    public function show(Espaceprestatairee $espaceprestatairee): Response
    {
        return $this->render('espaceprestataire/show.html.twig', [
            'espaceprestatairee' => $espaceprestatairee,
        ]);
    }

    /**
     * @Route("/{idprest}/edit", name="app_espaceprestataire_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Espaceprestatairee $espaceprestatairee, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Espaceprestatairee1Type::class, $espaceprestatairee);
        $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $file = $request->files->get('espaceprestatairee1')['logo'];
                $uploads_directory = $this->getParameter('uploads_directory');
                $filename = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move(
                    $uploads_directory,
                    $filename
                );
                $espaceprestatairee->setLogo($filename);
                $entityManager->persist($espaceprestatairee);
                $entityManager->flush();

        return $this->redirectToRoute('app_espaceprestataire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('espaceprestataire/edit.html.twig', [
            'espaceprestatairee' => $espaceprestatairee,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{idprest}", name="app_espaceprestataire_delete", methods={"POST"})
     */
    public function delete(Request $request, Espaceprestatairee $espaceprestatairee, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$espaceprestatairee->getIdprest(), $request->request->get('_token'))) {
            $entityManager->remove($espaceprestatairee);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_espaceprestataire_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/pdf/espace/download", name="espace_pdf")
     */
    public function packPdf(espaceprestataireRepository $espaceprestataireRepository)
    {
        // configuration
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->set('isHtml5ParserEnabled', true);
        $pdfOptions->set('isRemoteEnabled', true);

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('espaceprestataire/pdf.html.twig', [
            'espaceprestataire' => $espaceprestataireRepository->findAll(),
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A3', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => true
        ]);
    }
}