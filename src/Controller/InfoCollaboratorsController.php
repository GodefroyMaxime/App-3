<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InfoCollaboratorsController extends AbstractController
{
    #[Route('/info/collaborators', name: 'app_info_collaborators')]
    public function index(): Response
    {
        return $this->render('info_collaborators/index.html.twig', [
            'controller_name' => 'InfoCollaboratorsController',
        ]);
    }
}
