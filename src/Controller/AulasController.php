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
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @Route("/aulas")
 */
class AulasController extends AbstractController
{
    public function __construct(private ManagerRegistry $doctrine) {}

    /**
     * @Route("/", name="aulas_index", methods={"GET"})
     * 
     * @IsGranted("ROLE_USER")
     */
    public function index(Request $request, AulasRepository $aulasRepository, SessionInterface $session): Response
    {
        $edificio = $request->query->get('edificio', 0);
        if ($edificio==0) {
            $edificio = $session->get('id_edificio');
        }

        return $this->render('aulas/index.html.twig', [
            'aulas' => $aulasRepository->findBy(['id_edificio'=>$edificio], ['id_edificio'=>'ASC', 'id_piso'=>'ASC']),
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
            $entityManager = $this->doctrine->getManager();
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
            $this->doctrine->getManager()->flush();

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
            $entityManager = $this->doctrine->getManager();
            $entityManager->remove($aula);
            $entityManager->flush();
        }

        return $this->redirectToRoute('aulas_index', [], Response::HTTP_SEE_OTHER);
    }
}
