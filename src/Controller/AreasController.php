<?php

namespace App\Controller;

use App\Entity\Areas;
use App\Form\AreasType;
use App\Repository\AreasRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;


#[Route('/areas')]
class AreasController extends AbstractController
{
    #[Route('/', name: 'areas_index', methods: ['GET'])]
    public function index(AreasRepository $areasRepository): Response
    {
        return $this->render('areas/index.html.twig', [
            'areas' => $areasRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'areas_new', methods: ['GET','POST'])]
    public function new(Request $request): Response
    {
        $area = new Areas();
        $form = $this->createForm(AreasType::class, $area);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($area);
            $entityManager->flush();

            return $this->redirectToRoute('areas_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('areas/new.html.twig', [
            'area' => $area,
            'form' => $form,
        ]);
    }

    #[Route('/json', name: 'areas_json', methods: ['GET','POST'])]
    public function getAreasJson(AreasRepository $areasRepository): Response
    {
        $areas = $areasRepository->findAll();
        $responseArray = array();
        foreach($areas as $a){
            $responseArray[] = array(
                "id" => $a->getId(),
                "area" => $a->getArea()
            );
        };
        //dd($responseArray);
        return new JsonResponse($responseArray);
    }

    #[Route('/{id}', name: 'areas_show', methods: ['GET'])]
    public function show(Areas $area): Response
    {
        return $this->render('areas/show.html.twig', [
            'area' => $area,
        ]);
    }

    #[Route('/{id}/edit', name: 'areas_edit', methods: ['GET','POST'])]
    public function edit(Request $request, Areas $area): Response
    {
        $form = $this->createForm(AreasType::class, $area);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('areas_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('areas/edit.html.twig', [
            'area' => $area,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'areas_delete', methods: ['POST'])]
    public function delete(Request $request, Areas $area): Response
    {
        if ($this->isCsrfTokenValid('delete'.$area->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($area);
            $entityManager->flush();
        }

        return $this->redirectToRoute('areas_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/catedras', name: 'areas_catedras', methods: ['POST'])]
    public function getCatedras(int $id): Response
    {
        $area = $this->getDoctrine()->getRepository(Areas::class)->find($id);
        $catedras = $area->getCatedras();
        $responseArray = array();
        foreach($catedras as $catedra){
            $responseArray[] = array(
                "id" => $catedra->getId(),
                "nombre" => $catedra->getNombre()
            );
        };
        return new JsonResponse($responseArray);
    }


}
