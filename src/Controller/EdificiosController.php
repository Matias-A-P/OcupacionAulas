<?php

namespace App\Controller;

use App\Entity\Edificios;
use App\Entity\EdificiosPisos;
use App\Form\EdificiosType;
use App\Repository\EdificiosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\Persistence\ManagerRegistry;

#[Route('/edificios')]
class EdificiosController extends AbstractController
{
    public function __construct(private ManagerRegistry $doctrine)
    {
        
    }

    #[Route('/', name: 'edificios_index', methods: ['GET'])]
    public function index(EdificiosRepository $edificiosRepository): Response
    {
        return $this->render('edificios/index.html.twig', [
            'edificios' => $edificiosRepository->findBy([], ['Sede'=>'ASC']),
        ]);
    }

    #[Route('/new', name: 'edificios_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $edificio = new Edificios();

        $piso1 = new EdificiosPisos();
        $piso1->setIdEdificio($edificio);
        $piso1->setPiso('Planta Baja');
        $edificio->getEdificiosPisos()->add($piso1);

        $form = $this->createForm(EdificiosType::class, $edificio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($edificio);
            $entityManager->flush();

            return $this->redirectToRoute('edificios_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('edificios/new.html.twig', [
            'edificio' => $edificio,
            'form' => $form,
        ]);
    }

    #[Route('/json', name: 'edificios_json', methods: ['GET','POST'])]
    public function getEdificiosJson(EdificiosRepository $edificiosRepository): Response
    {
        $areas = $edificiosRepository->findAll();
        $responseArray = array();
        foreach($areas as $a){
            $responseArray[] = array(
                "id" => $a->getId(),
                "edificio" => $a->getEdificio()
            );
        };
        return new JsonResponse($responseArray);
    }

    #[Route('/{id}', name: 'edificios_show', methods: ['GET'])]
    public function show(Edificios $edificio): Response
    {
        return $this->render('edificios/show.html.twig', [
            'edificio' => $edificio,
        ]);
    }

    #[Route('/{id}/edit', name: 'edificios_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Edificios $edificio, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EdificiosType::class, $edificio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('edificios_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('edificios/edit.html.twig', [
            'edificio' => $edificio,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'edificios_delete', methods: ['POST'])]
    public function delete(Request $request, Edificios $edificio, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $edificio->getId(), $request->request->get('_token'))) {
            $entityManager->remove($edificio);
            $entityManager->flush();
        }

        return $this->redirectToRoute('edificios_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/{id}/pisos', name: 'edificios_pisos', methods: ['GET','POST'])]
    public function getPisos(int $id): Response
    {
        $edificio = $this->doctrine->getRepository(Edificios::class)->find($id);
        $pisos = $edificio->getEdificiosPisos();
        $responseArray = array();
        foreach ($pisos as $piso) {
            $responseArray[] = array(
                "id" => $piso->getId(),
                "piso" => $piso->getPiso()
            );
        };
        return new JsonResponse($responseArray);
    }
}
