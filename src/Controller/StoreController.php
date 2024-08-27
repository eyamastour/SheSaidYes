<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Data\SearchDataService;
use App\Entity\Espaceprestatairee;
use App\Form\SearchForm;
use App\Form\SearchFormService;
use App\Repository\espaceprestataireRepository;
use App\Repository\PacksRepository;
use App\Repository\ServicesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class StoreController extends AbstractController
{


    /** 
     * @Route("/store/pack", name="app_store")
     */
    public function index(PacksRepository $packsRepository,espaceprestataireRepository $espaceprestataireRepository, Request $request): Response
    {
        $data = new SearchData();
        $data->page = $request->get('page', 1);
        $form = $this->createForm(SearchForm::class, $data);
        $form->handleRequest($request);
        $packs = $packsRepository->findSearch($data);

        if ($request->get('ajax')) {
            return new JsonResponse([
                'content' => $this->renderView('store/packs.html.twig', ['data' => $packs]),
                'sorting' => $this->renderView('store/services_sorting.html.twig', ['data' => $packs]),
                'pagination' => $this->renderView('store/services_pagination.html.twig', ['data' => $packs])
            ]);
        }
        return $this->render('store/index.html.twig', [
            'packs' => $packsRepository->findAll(),
            'form' => $form->createView(),
            'data' => $packs,
            'profil' => $espaceprestataireRepository->findAll()
        ]);
    }

    /**
     * @Route("/store/pack/{id}", name="app_store_item")
     */
    public function PackItem(PacksRepository $packsRepository, $id, ServicesRepository $servicesRepository): Response
    {
        $pack = $packsRepository->find($id);
        $services = $servicesRepository->getPackServices($id);
        return $this->render('store/packItem.html.twig', [

            'data' => $pack,
            'services' => $services,
        ]);
    }

    /**
     * @Route("/store/service", name="app_store_service")
     */
    public function ServiceStore(ServicesRepository $servicesRepository, Request $request): Response
    {
        $data = new SearchDataService();
        $data->page = $request->get('page', 1);
        $form = $this->createForm(SearchFormService::class, $data);
        $form->handleRequest($request);
        $services = $servicesRepository->findSearch($data);


        if ($request->get('ajax')) {
            return new JsonResponse([
                'content' => $this->renderView('store/services.html.twig', ['data' => $services]),
                'sorting' => $this->renderView('store/services_sorting.html.twig', ['data' => $services]),
                'pagination' => $this->renderView('store/services_pagination.html.twig', ['data' => $services])
            ]);
        }
        return $this->render('store/storeService.html.twig', [

            'form' => $form->createView(),
            'data' => $services,
        ]);
    }

    /**
     * @Route("/store/service/{id}", name="app_store_item_service")
     */
    public function ServiceItem($id, ServicesRepository $servicesRepository,Espaceprestatairee $espaceprestatairee): Response
    {
        $service = $servicesRepository->find($id);


        return $this->render('store/serviceItem.html.twig', [

            'data' => $service,
            'profil' => $espaceprestatairee

        ]);
    }
    /**
     * @Route("store/profil/{idprest}", name="app_espaceprestataire_showw", methods={"GET"})
     */
    public function show(Espaceprestatairee $espaceprestatairee): Response
    {
        return $this->render('store/show.html.twig', [
            'profil' => $espaceprestatairee,
        ]);
    }
}