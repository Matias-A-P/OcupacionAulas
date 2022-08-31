<?php

namespace App\Controller;

use App\Entity\UserEdificios;
use App\Form\UserEdificiosType;
use App\Repository\UserEdificiosRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[Route('/usuario/edificios')]
class UserEdificiosController extends AbstractController
{
    #[Route('/', name: 'app_user_edificios_index', methods: ['GET'])]
    public function index(UserEdificiosRepository $userEdificiosRepository): Response
    {
        return $this->render('user_edificios/index.html.twig', [
            'user_edificios' => $userEdificiosRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_user_edificios_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ROOT')]
    public function new(Request $request, UserEdificiosRepository $userEdificiosRepository): Response
    {
        $userEdificio = new UserEdificios();
        $form = $this->createForm(UserEdificiosType::class, $userEdificio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userEdificiosRepository->add($userEdificio, true);

            return $this->redirectToRoute('app_user_edificios_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user_edificios/new.html.twig', [
            'user_edificio' => $userEdificio,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_edificios_show', methods: ['GET'])]
    public function show(UserEdificios $userEdificio): Response
    {
        return $this->render('user_edificios/show.html.twig', [
            'user_edificio' => $userEdificio,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edificios_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ROOT')]
    public function edit(Request $request, UserEdificios $userEdificio, UserEdificiosRepository $userEdificiosRepository): Response
    {
        $form = $this->createForm(UserEdificiosType::class, $userEdificio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userEdificiosRepository->add($userEdificio, true);

            return $this->redirectToRoute('app_user_edificios_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user_edificios/edit.html.twig', [
            'user_edificio' => $userEdificio,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_edificios_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ROOT')]
    public function delete(Request $request, UserEdificios $userEdificio, UserEdificiosRepository $userEdificiosRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$userEdificio->getId(), $request->request->get('_token'))) {
            $userEdificiosRepository->remove($userEdificio, true);
        }

        return $this->redirectToRoute('app_user_edificios_index', [], Response::HTTP_SEE_OTHER);
    }
}
