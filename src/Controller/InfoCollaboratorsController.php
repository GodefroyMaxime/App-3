<?php

namespace App\Controller;

use App\Entity\InfoCollaborators;
use App\Form\InfoCollaboratorsType;
use App\Repository\InfoCollaboratorsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/infocollaborators')]
class InfoCollaboratorsController extends AbstractController
{
    #[Route('/', name: 'app_info_collaborators_index', methods: ['GET'])]
    public function index(InfoCollaboratorsRepository $infoCollaboratorsRepository): Response
    {
        return $this->render('info_collaborators/index.html.twig', [
            'info_collaborators' => $infoCollaboratorsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_info_collaborators_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $infoCollaborator = new InfoCollaborators();
        $form = $this->createForm(InfoCollaboratorsType::class, $infoCollaborator);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($infoCollaborator);
            $entityManager->flush();

            return $this->redirectToRoute('app_info_collaborators_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('info_collaborators/new.html.twig', [
            'info_collaborator' => $infoCollaborator,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_info_collaborators_show', methods: ['GET'])]
    public function show(InfoCollaborators $infoCollaborator): Response
    {
        return $this->render('info_collaborators/show.html.twig', [
            'info_collaborator' => $infoCollaborator,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_info_collaborators_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, InfoCollaborators $infoCollaborator, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(InfoCollaboratorsType::class, $infoCollaborator);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_info_collaborators_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('info_collaborators/edit.html.twig', [
            'info_collaborator' => $infoCollaborator,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_info_collaborators_delete', methods: ['POST'])]
    public function delete(Request $request, InfoCollaborators $infoCollaborator, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$infoCollaborator->getId(), $request->request->get('_token'))) {
            $entityManager->remove($infoCollaborator);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_info_collaborators_index', [], Response::HTTP_SEE_OTHER);
    }
}
