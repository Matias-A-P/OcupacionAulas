<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EdificiosController extends AbstractController
{
    #[Route('/edificios', name: 'edificios')]
    public function index(): Response
    {
        return $this->render('edificios/index.html.twig', [
            'controller_name' => 'EdificiosController',
        ]);
    }
}
