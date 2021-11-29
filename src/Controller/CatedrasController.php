<?php

namespace App\Controller;

use App\Entity\Catedras;
use App\Form\CatedrasType;
use App\Repository\CatedrasRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/catedras")
 */
class CatedrasController extends AbstractController
{
    /**
     * @Route("/", name="catedras_index", methods={"GET"})
     */
    public function index(CatedrasRepository $catedrasRepository): Response
    {
        return $this->render('catedras/index.html.twig', [
            'catedras' => $catedrasRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="catedras_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $catedra = new Catedras();
        $form = $this->createForm(CatedrasType::class, $catedra);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($catedra);
            $entityManager->flush();

            return $this->redirectToRoute('catedras_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('catedras/new.html.twig', [
            'catedra' => $catedra,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="catedras_show", methods={"GET"})
     */
    public function show(Catedras $catedra): Response
    {
        return $this->render('catedras/show.html.twig', [
            'catedra' => $catedra,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="catedras_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Catedras $catedra): Response
    {
        $form = $this->createForm(CatedrasType::class, $catedra);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('catedras_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('catedras/edit.html.twig', [
            'catedra' => $catedra,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="catedras_delete", methods={"POST"})
     */
    public function delete(Request $request, Catedras $catedra): Response
    {
        if ($this->isCsrfTokenValid('delete'.$catedra->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($catedra);
            $entityManager->flush();
        }

        return $this->redirectToRoute('catedras_index', [], Response::HTTP_SEE_OTHER);
    }
}
