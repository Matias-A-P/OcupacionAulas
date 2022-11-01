<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Edificios;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AppController extends AbstractController
{
    public function __construct(private ManagerRegistry $doctrine) {}

    /**
     * @Route("/homepage", name="app_homepage")
     */
    public function index(Request $request, SessionInterface $session): Response
    {
        date_default_timezone_set("America/Buenos_Aires");
        //$facu = $this->getParameter('app.facultad');
        $facu = $request->getHost();
        $session->set('facultad', $facu);
        $edificios = $this->doctrine->getRepository(Edificios::class)->findBy(array('facultad' => $facu), array('Sede' => 'ASC'));

        return $this->render('app/index.html.twig', ['controller_name' => 'AppController', 'edificios' => $edificios, 'facultad' => $facu]);
    }
}
