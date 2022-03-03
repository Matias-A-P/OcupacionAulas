<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{

    /**
     * @Route("/homepage", name="app_homepage")
     */
    public function index(): Response
    {
        date_default_timezone_set("America/Buenos_Aires");
        return $this->render('app/index.html.twig', ['controller_name' => 'AppController']);
    }
}
