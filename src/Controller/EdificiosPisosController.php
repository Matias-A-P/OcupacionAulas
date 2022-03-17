<?php

namespace App\Controller;

use App\Entity\EdificiosPisos;
use App\Form\EdificiosPisosType;
use App\Repository\EdificiosPisosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/edificios_pisos')]
class EdificiosPisosController extends AbstractController
{
    #[Route('/', name: 'edificios_pisos_index', methods: ['GET'])]
    public function index(EdificiosPisosRepository $edificiosPisosRepository): Response
    {
        return $this->render('edificios_pisos/index.html.twig', [
            'edificios_pisos' => $edificiosPisosRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'edificios_pisos_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $edificiosPiso = new EdificiosPisos();
        $form = $this->createForm(EdificiosPisosType::class, $edificiosPiso);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($edificiosPiso);
            $entityManager->flush();

            return $this->redirectToRoute('edificios_pisos_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('edificios_pisos/new.html.twig', [
            'edificios_piso' => $edificiosPiso,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'edificios_pisos_show', methods: ['GET'])]
    public function show(EdificiosPisos $edificiosPiso): Response
    {
        return $this->render('edificios_pisos/show.html.twig', [
            'edificios_piso' => $edificiosPiso,
        ]);
    }

    #[Route('/{id}/edit', name: 'edificios_pisos_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EdificiosPisos $edificiosPiso, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EdificiosPisosType::class, $edificiosPiso);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('edificios_pisos_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('edificios_pisos/edit.html.twig', [
            'edificios_piso' => $edificiosPiso,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'edificios_pisos_delete', methods: ['POST'])]
    public function delete(Request $request, EdificiosPisos $edificiosPiso, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$edificiosPiso->getId(), $request->request->get('_token'))) {
            $entityManager->remove($edificiosPiso);
            $entityManager->flush();
        }

        return $this->redirectToRoute('edificios_pisos_index', [], Response::HTTP_SEE_OTHER);
    }
}
