<?php

namespace App\Controller;

use App\Entity\PropertySearch1;
use App\Entity\Reservationpack;
use App\Form\PropertySearchType;
use App\Form\StatsType;
use App\Repository\reservationpackRepository;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;


class ChartjsController extends AbstractController
{
    /**
     * @Route("/chartjs", name="app_chartjs")
     */
    public function index(reservationpackRepository $reservationpackRepository, Request $request)
    {
    }
  
}
