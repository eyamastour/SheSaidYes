<?php

namespace App\Controller;

use App\Entity\Packs;
use App\Entity\Services;
use App\Form\Services1Type;
use App\Repository\ServicesRepository;
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
 * @Route("/services")
 */
class ServicesController extends AbstractController
{
    /**
     * @Route("/getServices", name="all_services")
     */
    public function getServices(ServicesRepository $serviceRepository): Response
    {
        $services = $serviceRepository->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted =  $serializer->normalize($services);
        return new JsonResponse($formatted);
    }


    /**
     * @Route("/serviceDetails", name="service_detail")
     */
    public function serviceDetails(ServicesRepository $serviceRepository, Request $request)
    {
        $id = $request->get("id");
        $service = $serviceRepository->find($id);
        $encoder = new JsonEncode();
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getDescription();
        });
        $serializer = new Serializer([$normalizer], [$encoder]);
        $formatted = $serializer->normalize($service);
        return new JsonResponse($formatted);
    }



    /**
     * @Route("/addService", name="add_service")
    
     */
    public function addService(Request $request)
    {
        $service = new Services();
        $nom = $request->query->get("nom");
        $description = $request->query->get("description");
        $prix = $request->query->get("prix");
        $image = $request->query->get("image");
        $categorie = $request->query->get("categorie");
        $iduser = $request->query->get("iduser");
        $idPack  = $request->query->get("idPack ");

        $promo = $request->query->get("promo");
        $pos1 = $request->query->get("pos1");
        $pos2 = $request->query->get("pos2");
        $idprest  = $request->query->get("idprest");


        $em = $this->getDoctrine()->getManager();


        $service->setNom($nom);
        $service->setDescription($description);
        $service->setPrix($prix);
        $service->setImage($image);
        $service->setCategorie($categorie);
        $service->setIduser($iduser);
        $service->setIdpack($idPack);
        $service->setPromo($promo);
        $service->setPos1($pos1);
        $service->setPos2($pos2);
        $service->setIdprest($idprest);

        $em->persist($service);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($service);
        return new JsonResponse($formatted);
    }



    /**
     * @Route("/updateService", name="update_service")

     */
    public function updateService(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $service = $this->getDoctrine()->getManager()
            ->getRepository(Services::class)
            ->find($request->get("id"));


        $service->setNom($request->get("nom"));
        $service->setDescription($request->get("description"));
        $service->setPrix($request->get("prix"));
        $service->setImage($request->get("image"));
        $service->setCategorie($request->get("categorie"));
        $service->setIduser($request->get("iduser"));
        $pack = $this->getDoctrine()->getManager()
            ->getRepository(Packs::class)
            ->find($request->get(("idPack")));


        $service->setIdpack($pack);
        $service->setPromo($request->get("promo"));
        $service->setPos1($request->get("pos1"));
        $service->setPos2($request->get("pos2"));
        $service->setIdprest($request->get("idprest"));

        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($service);
        return new JsonResponse("service a ete modifiee avec success.");
    }



    /**
     * @Route("/deleteService", name="delete_service")
     */

    public function deleteService(Request $request)
    {
        $id = $request->get("id");

        $em = $this->getDoctrine()->getManager();
        $service = $em->getRepository(Services::class)->find($id);
        if ($service != null) {
            $em->remove($service);
            $em->flush();

            $serialize = new Serializer([new ObjectNormalizer()]);
            $formatted = $serialize->normalize("service a ete supprimee avec success.");
            return new JsonResponse($formatted);
        }
        return new JsonResponse("id service invalide.");
    }




    //********************************************************************************** */



    /**
     * @Route("/", name="app_services_index", methods={"GET"})
     */
    public function index(ServicesRepository $servicesRepository): Response
    {
        $idUser = $this->getUser()->getId();
        $service = $servicesRepository->getPackUser($idUser);


        return $this->render('services/index.html.twig', [
            'services' => $service,
        ]);
    }

    /**
     * @Route("/new", name="app_services_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ServicesRepository $servicesRepository, EntityManagerInterface $entityManager, FlashyNotifier $flashy): Response
    {
        $service = new Services();
        $form = $this->createForm(Services1Type::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $request->files->get('services1')['image'];
            $uploads_directory = $this->getParameter('uploads_directory');
            $filename = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move(
                $uploads_directory,
                $filename
            );
            $service->setImage($filename);
            $service->setIduser($this->getUser()->getId());
            $entityManager->persist($service);
            $entityManager->flush();
            $flashy->success('Service added successfully!');

            return $this->redirectToRoute('app_services_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('services/new.html.twig', [
            'service' => $service,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_services_show", methods={"GET"})
     */
    public function show(Services $service): Response
    {
        return $this->render('services/show.html.twig', [
            'service' => $service,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_services_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Services $service, ServicesRepository $servicesRepository, EntityManagerInterface $entityManager, FlashyNotifier $flashy): Response
    {
        $form = $this->createForm(Services1Type::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $request->files->get('services1')['image'];
            $uploads_directory = $this->getParameter('uploads_directory');
            $filename = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move(
                $uploads_directory,
                $filename
            );
            $service->setImage($filename);
            $service->setIduser($this->getUser()->getId());
            $entityManager->persist($service);
            $entityManager->flush();
            $flashy->info('Service updated successfully!');

            return $this->redirectToRoute('app_services_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('services/edit.html.twig', [
            'service' => $service,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_services_delete", methods={"POST"})
     */
    public function delete(Request $request, Services $service, ServicesRepository $servicesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $service->getId(), $request->request->get('_token'))) {
            $servicesRepository->remove($service);
        }

        return $this->redirectToRoute('app_services_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/pdf/service/download", name="services_pdf")
     */
    public function packPdf(ServicesRepository $servicesRepository)
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->set('isHtml5ParserEnabled', true);
        $pdfOptions->set('isRemoteEnabled', true);

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('services/pdfListServices.html.twig', [
            'services' => $servicesRepository->findAll(),
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
