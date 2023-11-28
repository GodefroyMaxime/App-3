<?php

namespace App\Controller;

use App\Entity\InfoCollaborators;
use App\Form\InfoCollaborators1Type;
use App\Repository\InfoCollaboratorsRepository;
use App\Service\ChartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/infoCollaboratorsRO')]
class InfoCollaboratorsReadOnlyController extends AbstractController
{
    #[Route('/', name: 'app_info_collaborators_read_only_index', methods: ['GET'])]
    public function index(InfoCollaboratorsRepository $infoCollaboratorsRepository): Response
    {
        return $this->render('info_collaborators_read_only/index.html.twig', [
            'info_collaborators' => $infoCollaboratorsRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_info_collaborators_read_only_show', methods: ['GET'])]
    public function show(InfoCollaboratorsRepository $infoCollaborator): Response
    {
        return $this->render('info_collaborators_read_only/show.html.twig', [
            'info_collaborator' => $infoCollaborator,
        ]);
    }

    #[Route('/donwloadChartImage', name: 'app_info_collaborators_read_only_donwloadChartImage', methods: ['POST'])]
    public function donwloadChartImage(InfoCollaboratorsRepository $infoCollaboratorsRepository, Request $request, ChartService $chart): Response
    {
        $dataImage1 = $request->request->get('image1'); 
        $dataImage2 = $request->request->get('image2'); 
        $dataImage3 = $request->request->get('image3'); 
        $matricule = $request->request->get('matricule'); 
        
        $chart->downloadDataImage($dataImage1, 'chartImage', 'GraphSalaire'.$matricule, 'png');
        $chart->downloadDataImage($dataImage2, 'chartImage', 'BarGraphProtection'.$matricule, 'png');
        $chart->downloadDataImage($dataImage3, 'chartImage', 'PieGraphProtection'.$matricule, 'png');
            

        return $this->render('info_collaborators_read_only/index.html.twig', [
            'info_collaborators' => $infoCollaboratorsRepository->findAll(),
        ]);
    }
}
