<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Edificios;
use Doctrine\Persistence\ManagerRegistry;

class AppController extends AbstractController
{
    public function __construct(private ManagerRegistry $doctrine) {}

    /**
     * @Route("/homepage", name="app_homepage")
     */
    public function index(): Response
    {
        date_default_timezone_set("America/Buenos_Aires");

        $edificios = $this->doctrine->getRepository(Edificios::class)->findAll();

        return $this->render('app/index.html.twig', ['controller_name' => 'AppController', 'edificios' => $edificios]);
    }
}
