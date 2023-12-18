<?php

namespace App\Controller;

use App\Entity\InfoCollaborators;
use App\Repository\InfoCollaboratorsRepository;
use App\Service\BSIService;
use App\Service\ChartService;
use App\Service\PDFService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InfoCollaboratorsReadOnlyController extends AbstractController
{
    #[Route('/infoCollaboratorsRO', name: 'app_info_collaborators_read_only_index', methods: ['GET'])]
    public function index(InfoCollaboratorsRepository $infoCollaboratorsRepository): Response
    {
        return $this->render('info_collaborators_read_only/index.html.twig', [
            'info_collaborators' => $infoCollaboratorsRepository->findAll(),
        ]);
    }    

    #[Route('/infoCollaboratorsRO/{id}', name: 'app_info_collaborators_read_only_show', methods: ['GET'])]
    public function show(InfoCollaborators $infoCollaborator): Response
    {
        return $this->render('info_collaborators_read_only/show.html.twig', [
            'info_collaborator' => $infoCollaborator,
        ]);
    }  

    #[Route('/infoCollaboratorsRO/donwloadChartImage', name: 'app_info_collaborators_read_only_donwloadChartImage', methods: ['POST'])]
    public function donwloadChartImage(InfoCollaboratorsRepository $infoCollaboratorsRepository, Request $request, ChartService $chart): Response
    {
        $dataImage1 = $request->request->get('image1');
        $dataImage2 = $request->request->get('image2');
        $dataImage3 = $request->request->get('image3'); 
        $matricule = $request->request->get('matricule'); 
        
        
        $chart->downloadDataImage($dataImage1, 'chartImage', 'PieChartSalaire'.$matricule, 'png');
        $chart->downloadDataImage($dataImage2, 'chartImage', 'BarChartProtection'.$matricule, 'png');
        $chart->downloadDataImage($dataImage3, 'chartImage', 'PieChartProtection'.$matricule, 'png');
            

        return $this->render('info_collaborators_read_only/index.html.twig', [
            'info_collaborators' => $infoCollaboratorsRepository->findAll(),
        ]);
    }
    
    #[Route('/bsi/{matricule}', name: 'app_info_collaborators_read_only_bsi', methods: ['POST'])]
    public function bsi(PDFService $pdf, BSIService $bsi, Request $request): Response
    {    
        
        set_time_limit(0);
        ini_set('memory_limit', '10000M');
        
        $info = [json_decode($request->request->get('infos'), true)];  
        $info_collaborators = $bsi->addPathChartImageTable($info);
        $chart = [];


        foreach ($info_collaborators as $key => $value) {
            $matricule = $value['matricule'];
            
            $pieChartProtection = $value['pieChartProtection'];

            if(!file_exists('.'.$pieChartProtection)) {
                $chart += [$value['matricule'] => false];
            } else {
                $chart += [$value['matricule'] => true];
            }
        }

        $html = $this->renderView('info_collaborators_read_only/bsi.html.twig', [
            'info_collaborators' => $info_collaborators,
        ]);  

        if (in_array(false, $chart)) {
             return new Response('Image manquante',Response::HTTP_INTERNAL_SERVER_ERROR);
        } else {
            return new Response(
                $pdf->generatePDF("landscape", $html, 'BSI_'.$matricule),
                Response::HTTP_OK,
                ['Content-Type' => 'application/pdf']
            );
        }             
    }   
 
    
    #[Route('/AllBSI', name: 'app_info_collaborators_read_only_all_bsi', methods: ['POST'])]
    public function allbsi(PDFService $pdf, BSIService $bsi, Request $request): Response
    {        
        set_time_limit(0);
        ini_set('memory_limit', '10000M');
        
        $infos = json_decode($request->request->get('infos'), true); 
        $info_collaborators = $bsi->addPathChartImageTable($infos);
        $chart = [];
        foreach ($info_collaborators as $key => $value) {
            
            $pieChartProtection = $value['pieChartProtection'];

            if(!file_exists('.'.$pieChartProtection)) {
                $chart += [$value['matricule'] => false];
            } else {
                $chart += [$value['matricule'] => true];
            }
        }

        $html = $this->renderView('info_collaborators_read_only/bsi.html.twig', [
            'info_collaborators' => $info_collaborators, 
        ]);  

        if (in_array(false, $chart)) {
             return new Response('Image manquante',Response::HTTP_INTERNAL_SERVER_ERROR);
        } else {
            return new Response(
                $pdf->generatePDF("landscape", $html, 'AllBSI'),
                Response::HTTP_OK,
                ['Content-Type' => 'application/pdf']
            );
        }
    }            
}