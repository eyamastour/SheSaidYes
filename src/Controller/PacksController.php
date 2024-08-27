<?php

namespace App\Controller;

use App\Entity\Packs;
use App\Form\Packs1Type;
use App\Repository\PacksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use MercurySeries\FlashyBundle\FlashyNotifier;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/packs")
 */
class PacksController extends AbstractController
{

    /**
     * @Route("/getPacks", name="all_packs")
     */
    public function getPacks(PacksRepository $packsRepository): Response
    {
        $packs = $packsRepository->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted =  $serializer->normalize($packs);
        return new JsonResponse($formatted);
    }

    /**
     * @Route("/packDetails", name="pack_detail")
     */
    public function packDetails(PacksRepository $packsRepository, Request $request)
    {
        $id = $request->get("id");
        $pack = $packsRepository->find($id);
        $encoder = new JsonEncode();
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getDescription();
        });
        $serializer = new Serializer([$normalizer], [$encoder]);
        $formatted = $serializer->normalize($pack);
        return new JsonResponse($formatted);
    }


    /**
     * @Route("/addPack", name="add_pack")
    
     */
    public function addPack(Request $request)
    {
        $pack = new Packs();
        $nom = $request->query->get("nom");
        $description = $request->query->get("description");
        $prix = $request->query->get("prix");
        $image = $request->query->get("image");
        $iduser = $request->query->get("iduser");
        $promo = $request->query->get("promo");
        $pos1 = $request->query->get("pos1");
        $pos2 = $request->query->get("pos2");



        $em = $this->getDoctrine()->getManager();


        $pack->setNom($nom);
        $pack->setDescription($description);
        $pack->setPrix($prix);
        $pack->setImage($image);
        $pack->setIduser($iduser);
        $pack->setPromo($promo);
        $pack->setPos1($pos1);
        $pack->setPos2($pos2);


        $em->persist($pack);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($pack);
        return new JsonResponse($formatted);
    }


    /**
     * @Route("/updatePack", name="update_pack")

     */
    public function updatePack(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $pack = $this->getDoctrine()->getManager()
            ->getRepository(Packs::class)
            ->find($request->get("id"));

        $pack->setNom($request->get("nom"));
        $pack->setDescription($request->get("description"));
        $pack->setPrix($request->get("prix"));
        $pack->setImage($request->get("image"));
        $pack->setIduser($request->get("iduser"));
        $pack->setPromo($request->get("promo"));
        $pack->setPos1($request->get("pos1"));
        $pack->setPos2($request->get("pos2"));


        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($pack);
        return new JsonResponse("pack a ete modifiee avec success.");
    }




    /**
     * @Route("/deletePack", name="delete_pack")
     */

    public function deletePack(Request $request)
    {
        $id = $request->get("id");

        $em = $this->getDoctrine()->getManager();
        $pack = $em->getRepository(Packs::class)->find($id);
        if ($pack != null) {
            $em->remove($pack);
            $em->flush();

            $serialize = new Serializer([new ObjectNormalizer()]);
            $formatted = $serialize->normalize("pack a ete supprimee avec success.");
            return new JsonResponse($formatted);
        }
        return new JsonResponse("id pack invalide.");
    }




    //*************************************************************************************************************** */
    /**
     * @Route("/", name="app_packs_index", methods={"GET"})
     */
    public function index(PacksRepository $packsRepository): Response
    {
        $idUser = $this->getUser()->getId();
        $pack = $packsRepository->getPackUser($idUser);

        return $this->render('packs/index.html.twig', [
            'packs' => $pack,
        ]);
    }



    /**
     * @Route("/new", name="app_packs_new", methods={"GET", "POST"})
     */
    public function new(Request $request, PacksRepository $packsRepository, EntityManagerInterface $entityManager, FlashyNotifier $flashy): Response
    {
        $pack = new Packs();
        $form = $this->createForm(Packs1Type::class, $pack);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $request->files->get('packs1')['image'];
            $uploads_directory = $this->getParameter('uploads_directory');
            $filename = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move(
                $uploads_directory,
                $filename
            );
            $pack->setImage($filename);
            $pack->setIduser($this->getUser()->getId());
            $entityManager->persist($pack);
            $entityManager->flush();
            $flashy->success('Pack added successfully!');


            return $this->redirectToRoute('app_packs_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('packs/new.html.twig', [
            'pack' => $pack,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_packs_show", methods={"GET"})
     */
    public function show(Packs $pack): Response
    {
        return $this->render('packs/show.html.twig', [
            'pack' => $pack,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_packs_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Packs $pack, PacksRepository $packsRepository, EntityManagerInterface $entityManager, FlashyNotifier $flashy): Response
    {
        $form = $this->createForm(Packs1Type::class, $pack);
        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {

            if ($pack->getImage() != "null") {
                $file = $request->files->get('packs1')['image'];
                $uploads_directory = $this->getParameter('uploads_directory');
                $filename = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move(
                    $uploads_directory,
                    $filename
                );
                $pack->setImage($filename);
            }

            $pack->setIduser($this->getUser()->getId());
            $entityManager->persist($pack);
            $entityManager->flush();
            $this->addFlash('infoU', 'Updated successfully');
            $flashy->info('Pack updated successfully!');
            return $this->redirectToRoute('app_packs_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('packs/edit.html.twig', [
            'pack' => $pack,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_packs_delete")
     */
    public function delete(Request $request, Packs $pack, PacksRepository $packsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $pack->getId(), $request->request->get('_token'))) {
            $packsRepository->remove($pack);
        }

        return $this->redirectToRoute('app_packs_index', [], Response::HTTP_SEE_OTHER);
    }





    /**
     * @Route("/pdf/download", name="packs_pdf")
     */
    public function packPdf(PacksRepository $packsRepository)
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->set('isHtml5ParserEnabled', true);
        $pdfOptions->set('isRemoteEnabled', true);

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('packs/pdfListPack.html.twig', [
            'packs' => $packsRepository->findAll(),
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => true
        ]);
    }
}
