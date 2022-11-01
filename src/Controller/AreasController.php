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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


#[Route('/areas')]
class AreasController extends AbstractController
{
    public function __construct(private ManagerRegistry $doctrine) {}

    #[Route('/', name: 'areas_index', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function index(AreasRepository $areasRepository, SessionInterface $session): Response
    {
        return $this->render('areas/index.html.twig', [
            'areas' => $areasRepository->findBy([], ['facultad'=>'ASC']),
            //'areas' => $areasRepository->findBy(['facultad'=>$session->get('facultad')]),
        ]);
    }

    #[Route('/new', name: 'areas_new', methods: ['GET','POST'])]
    #[IsGranted('ROLE_ROOT')]
    public function new(Request $request): Response
    {
        $area = new Areas();
        $form = $this->createForm(AreasType::class, $area);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->doctrine->getManager();
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
    public function getAreasJson(AreasRepository $areasRepository, SessionInterface $session): Response
    {
        $areas = $areasRepository->findBy(['facultad'=>$session->get('facultad')]);
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
    #[IsGranted('ROLE_ROOT')]
    public function edit(Request $request, Areas $area): Response
    {
        $form = $this->createForm(AreasType::class, $area);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->doctrine->getManager()->flush();

            return $this->redirectToRoute('areas_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('areas/edit.html.twig', [
            'area' => $area,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'areas_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ROOT')]
    public function delete(Request $request, Areas $area): Response
    {
        if ($this->isCsrfTokenValid('delete'.$area->getId(), $request->request->get('_token'))) {
            $entityManager = $this->doctrine->getManager();
            $entityManager->remove($area);
            $entityManager->flush();
        }

        return $this->redirectToRoute('areas_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/catedras', name: 'areas_catedras', methods: ['POST'])]
    public function getCatedras(int $id): Response
    {
        $area = $this->doctrine->getRepository(Areas::class)->find($id);
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
