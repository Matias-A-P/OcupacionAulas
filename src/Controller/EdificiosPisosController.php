<?php

namespace App\Controller;

use App\Entity\EdificiosPisos;
use App\Entity\Edificios;
use App\Form\EdificiosPisosType;
use App\Repository\EdificiosPisosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

#[Route('/edificios_pisos')]
class EdificiosPisosController extends AbstractController
{
    public function __construct(private ManagerRegistry $doctrine){
        
    }

    #[Route('/', name: 'edificios_pisos_index', methods: ['GET'])]
    public function index(Request $request, EdificiosPisosRepository $edificiosPisosRepository): Response
    {
        $edificio = $request->query->get('edificio', 0);
        if ($edificio > 0) {
            $pisos = $edificiosPisosRepository->findBy(['id_edificio' => $edificio]);
        } else {
            $pisos = $edificiosPisosRepository->findAll();
        }
        return $this->render('edificios_pisos/index.html.twig', [
            'edificios_pisos' => $pisos,
        ]);
    }

    #[Route('/new', name: 'edificios_pisos_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $edificio = $request->query->get('edificio', 0);
        $edificiosPiso = new EdificiosPisos();
        $edificiosPiso->setIdEdificio($this->doctrine->getRepository(Edificios::class)->find($edificio));
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
        if ($this->isCsrfTokenValid('delete' . $edificiosPiso->getId(), $request->request->get('_token'))) {
            $entityManager->remove($edificiosPiso);
            $entityManager->flush();
        }

        return $this->redirectToRoute('edificios_pisos_index', [], Response::HTTP_SEE_OTHER);
    }
}
