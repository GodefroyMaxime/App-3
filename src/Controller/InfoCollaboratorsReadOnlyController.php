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
    public function donwloadChartImage(Request $request, ChartService $chart): Response
    {
        $dataImage = $request->request->get('image'); 
        $number = $request->request->get('nbChart'); 
        
        return new Response(
            $chart->downloadDataImage($dataImage, 'chartImage', 'test'.$number, 'png'),
            Response::HTTP_OK,
            ['Content-Type' => 'application/image']
        );
    }
}
