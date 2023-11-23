<?php

namespace App\Controller;

use App\Service\ChartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrameController extends AbstractController
{
    #[Route('/trame', name: 'app_trame')]
    public function index(): Response
    {
        return $this->render('trame/index.html.twig', [
            'controller_name' => 'TrameController',
        ]);
    }
    
    #[Route('/trame/donwloadChartImage', name: 'app_trame_donwloadChartImage', methods: ['POST'])]
    public function donwloadChartImage(Request $request, ChartService $chart): Response
    {
        $dataImage = $request->request->get('image'); 
        $number = $request->request->get('number'); 
        $chart->downloadDataImage($dataImage, 'chartImage', 'test'.$number, 'png');
        return $this->render('trame/index.html.twig', [
            'controller_name' => 'TrameController',
        ]);
    }
}
