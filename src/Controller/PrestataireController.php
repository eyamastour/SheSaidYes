<?php

namespace App\Controller;

use App\Repository\espaceprestataireRepository;
use App\Repository\reservationpackRepository;
use App\Repository\reservationserviceRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;


class PrestataireController extends AbstractController
{
    /**
     * @Route("/prestataire", name="app_prestataire")
     */
    public function index(espaceprestataireRepository $espaceprestataireRepository, reservationpackRepository $reservationpackRepository, ChartBuilderInterface $chartBuilder, reservationserviceRepository $reservationserviceRepository, UtilisateurRepository $utilisateurRepository): Response
    {



        $reservationpacks = $reservationpackRepository->findAll();
        $reservationservice = $reservationserviceRepository->findAll();
        $clients = $utilisateurRepository->findAll();
        $prest = $espaceprestataireRepository->findAll();


        $sommepack = 0.0;
        $sommeservice = 0.0;
        $nbrclient = 0.0;
        $nbrprest = 0.0;
        $somme = 0.0;
        foreach ($clients as $clients) {

            $nbrclient += 1;
        }
        foreach ($prest as $prest) {

            $nbrprest += 1;
        }
        foreach ($reservationpacks as $reservationpack) {

            $sommepack += $reservationpack->getPrixrespack();
        }
        foreach ($reservationpacks as $reservationpack) {

            $sommepack += $reservationpack->getPrixrespack();
        }

        foreach ($reservationservice as $reservationservice) {

            $sommeservice += $reservationservice->getPrixresserv();
        }

        $somme += $sommepack + $sommeservice;

        $reservationpacks = $reservationpackRepository->findAll();

        foreach ($reservationpacks as $reservationpack) {
            $labels[] = $reservationpack->getDate();
            $data[] = $reservationpack->getPrixrespack();
        }

        sort($labels);

        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);

        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Prix des réservations',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'is3D' => true,
                    'data' => $data,
                ],
            ],
        ]);

        $chart->setOptions([]);


        $years = $reservationpackRepository->allYears();

        $allYear = $reservationpackRepository->statsForAllYears();
        // dd($allYear);

        $datas = $reservationpackRepository->statsForeveryYear("");



        $user=$this->getUser();


        return $this->render('prestataire/allyears.html.twig', [
            'date' => $labels,
            'donnee' => $data,
            'chart' => $chart,
            'somme' => $somme,
            'datas' => $datas,
            'years' => $years,
            'stats' => $allYear,
            'user' => $user


        ]);
    }



    /**
     * 
     * @Route("/everyYear/{annee}", name="charts_everyYear", methods={"GET", "POST"})
     */
    public function statsForYears($annee, reservationpackRepository $reservationpackRepository, ChartBuilderInterface $chartBuilder, reservationserviceRepository $reservationserviceRepository)
    {
        $reservationpacks = $reservationpackRepository->findAll();
        $reservationservice = $reservationserviceRepository->findAll();

        $sommepack = 0.0;
        $sommeservice = 0.0;
        $somme = 0.0;

        foreach ($reservationpacks as $reservationpack) {

            $sommepack += $reservationpack->getPrixrespack();
        }

        foreach ($reservationservice as $reservationservice) {

            $sommeservice += $reservationservice->getPrixresserv();
        }

        $somme += $sommepack + $sommeservice;

        $reservationpacks = $reservationpackRepository->findAll();

        foreach ($reservationpacks as $reservationpack) {
            $labels[] = $reservationpack->getDate();
            $data[] = $reservationpack->getPrixrespack();
        }

        sort($labels);

        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);

        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Prix des réservations',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'is3D' => true,
                    'data' => $data,
                ],
            ],
        ]);

        $chart->setOptions([]);




        $datas = array();

        $years = $reservationpackRepository->allYears();

        $allYear = $reservationpackRepository->statsForAllYears();

        $datas = $reservationpackRepository->statsForeveryYear($annee);


        $reservationpackRepository->statsForeveryYear($annee);
        $user=$this->getUser();
        return $this->render('prestataire/index.html.twig', [
            'datas' => $datas,
            'years' => $years,
            'stats' => $allYear,
            'date' => $labels,
            'donnee' => $data,
            'chart' => $chart,
            'somme' => $somme,
            'user' => $user
        ]);
    }


    /**
     *  
     * @Route("allYears/", name="charts_allYear")
     */
    public function statsForAllYears(reservationpackRepository $reservationpackRepository, ChartBuilderInterface $chartBuilder, reservationserviceRepository $reservationserviceRepository)
    {


        $reservationpacks = $reservationpackRepository->findAll();
        $reservationservice = $reservationserviceRepository->findAll();

        $sommepack = 0.0;
        $sommeservice = 0.0;
        $somme = 0.0;

        foreach ($reservationpacks as $reservationpack) {

            $sommepack += $reservationpack->getPrixrespack();
        }

        foreach ($reservationservice as $reservationservice) {

            $sommeservice += $reservationservice->getPrixresserv();
        }

        $somme += $sommepack + $sommeservice;

        $reservationpacks = $reservationpackRepository->findAll();

        foreach ($reservationpacks as $reservationpack) {
            $labels[] = $reservationpack->getDate();
            $data[] = $reservationpack->getPrixrespack();
        }

        sort($labels);

        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);

        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Prix des réservations',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'is3D' => true,
                    'data' => $data,
                ],
            ],
        ]);

        $chart->setOptions([]);

        $years = $reservationpackRepository->allYears();


        $allYear = $reservationpackRepository->statsForAllYears();

        $user=$this->getUser();
        return $this->render('prestataire/allyears.html.twig', [
            'years' => $years,

            'stats' => $allYear,
            'date' => $labels,
            'donnee' => $data,
            'chart' => $chart,
            'somme' => $somme,
            'nbrclient' => $nbrclient,
            'nbrprest' => $nbrprest,
            'user'=>$user
        ]);
    }
}
