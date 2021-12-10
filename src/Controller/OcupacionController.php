<?php

namespace App\Controller;

use App\Entity\Ocupacion;
use App\Form\OcupacionType;
use App\Repository\OcupacionRepository;
use App\Entity\Aulas;
use DateInterval;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ocupacion")
 */
class OcupacionController extends AbstractController
{
    /**
     * @Route("/", name="ocupacion_index", methods={"GET"})
     */
    public function index(Request $request, OcupacionRepository $ocupacionRepository): Response
    {
        //$dia->setTimezone(new TimeZones::getName("America/Buenos_Aires"));    
        date_default_timezone_set("America/Buenos_Aires");
        $dia = new \DateTime('now');
        if ($request->query->has('dia')) {
            $dia = new \DateTime($request->query->get('dia'));
        }
        $vista = 'dia';
        if ($request->query->has('vista')) {
            $vista = $request->query->get('vista');
        }
        $aulas = $this->getDoctrine()->getRepository(Aulas::class)->findAll();
        $arrOcup = [];
        $i = 0;
        $diff7Day = new DateInterval('P7D');
        $dia2 = $dia;
        $dia2->add($diff7Day);
        foreach ($aulas as $aula) {
            if ($vista = 'semanal') {
                $arrOcup[$i] = $ocupacionRepository->getOcupacionesSemana($dia, $dia2, $aula->getId());
            } else {
                $arrOcup[$i] = $ocupacionRepository->getOcupacionesDia($dia, $aula->getId());
            }

            if (empty($arrOcup[$i])) {
                $ocup = new Ocupacion();
                $ocup->setIdAula($aula);
                $arrOcup[$i] = array($ocup);
            }
            $i++;
        }
        if ($vista = 'semanal') {
            return $this->render('ocupacion/semanal.html.twig', [
                'ocupacions' => $arrOcup,
                'fecha' => $dia
            ]);
        } else {
            return $this->render('ocupacion/index.html.twig', [
                'ocupacions' => $arrOcup,
                'fecha' => $dia
            ]);
        }
    }

    /**
     * @Route("/new/", name="ocupacion_new", methods={"GET","POST"})
     */
    ///{aula}/{hora}   , int $aula=0, string $hora
    public function new(Request $request): Response
    {
        if ($request->query->has('aula')) {
            $aula = $request->query->get('aula');
        } else {
            $aula = 1;
        };
        if ($request->query->has('dia')) {
            $dia = new \DateTime($request->query->get('dia'));
        } else {
            $dia = new \DateTime();
        };
        if ($request->query->has('hora')) {
            $hora = $request->query->get('hora');
        } else {
            $hora = '14:00';
        };

        $ocupacion = new Ocupacion();
        $ocupacion->setIdAula($this->getDoctrine()->getRepository(Aulas::class)->find($aula));
        $ocupacion->setFecha($dia);
        $ocupacion->setHoraInicio(new \DateTime($hora));
        $ocupacion->setHoraFin(new \DateTime($hora));
        $form = $this->createForm(OcupacionType::class, $ocupacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ocupacion);
            $entityManager->flush();

            return $this->redirectToRoute('ocupacion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ocupacion/new.html.twig', [
            'ocupacion' => $ocupacion,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="ocupacion_show", methods={"GET"})
     */
    public function show(Ocupacion $ocupacion): Response
    {
        return $this->render('ocupacion/show.html.twig', [
            'ocupacion' => $ocupacion,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="ocupacion_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Ocupacion $ocupacion): Response
    {
        $form = $this->createForm(OcupacionType::class, $ocupacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('ocupacion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ocupacion/edit.html.twig', [
            'ocupacion' => $ocupacion,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="ocupacion_delete", methods={"POST"})
     */
    public function delete(Request $request, Ocupacion $ocupacion): Response
    {
        if ($this->isCsrfTokenValid('delete' . $ocupacion->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($ocupacion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('ocupacion_index', [], Response::HTTP_SEE_OTHER);
    }
}
