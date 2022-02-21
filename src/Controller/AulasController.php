<?php

namespace App\Controller;

use App\Entity\Aulas;
use App\Form\AulasType;
use App\Repository\AulasRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/aulas")
 */
class AulasController extends AbstractController
{
    /**
     * @Route("/", name="aulas_index", methods={"GET"})
     * 
     * @IsGranted("ROLE_USER")
     */
    public function index(AulasRepository $aulasRepository): Response
    {
        $session = $this->get('session');
        $edificio = $session->get('id_edificio');

        return $this->render('aulas/index.html.twig', [
            'aulas' => $aulasRepository->findBy(['id_edificio'=>$edificio]),
        ]);
    }

    /**
     * @Route("/new", name="aulas_new", methods={"GET","POST"})
     * 
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(Request $request): Response
    {
        $aula = new Aulas();
        $form = $this->createForm(AulasType::class, $aula);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($aula);
            $entityManager->flush();

            return $this->redirectToRoute('aulas_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('aulas/new.html.twig', [
            'aula' => $aula,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="aulas_show", methods={"GET"})
     * 
     * @IsGranted("ROLE_USER")
     */
    public function show(Aulas $aula): Response
    {
        return $this->render('aulas/show.html.twig', [
            'aula' => $aula,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="aulas_edit", methods={"GET","POST"})
     * 
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Aulas $aula): Response
    {
        $form = $this->createForm(AulasType::class, $aula);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('aulas_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('aulas/edit.html.twig', [
            'aula' => $aula,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="aulas_delete", methods={"POST"})
     * 
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Aulas $aula): Response
    {
        if ($this->isCsrfTokenValid('delete'.$aula->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($aula);
            $entityManager->flush();
        }

        return $this->redirectToRoute('aulas_index', [], Response::HTTP_SEE_OTHER);
    }
}
