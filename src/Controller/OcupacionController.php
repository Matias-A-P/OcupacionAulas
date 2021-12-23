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
        if ($request->query->has('dia')) {
            $dia = $request->query->get('dia');
        } else {
            $dia = date('now');
        }
        if ($request->query->has('vista')) {
            $vista = $request->query->get('vista');
        } else {
            $vista = 'dia';
        }
        $aulas = $this->getDoctrine()->getRepository(Aulas::class)->findAll();
        $arrOcup = [];
        $i = 0;
        //$lunes = new \DateTime($dia)->add(new \DateInterval((intval(date('w', $dia))-1)."D"));
        // $sabado = new \DateTime($lunes)->add(new \DateInterval("5D"));
        $w = strtotime("w", strtotime($dia))-1;
        $lun = date("Y-m-d", strtotime($dia) - strtotime("+1 day")); //strtotime($dia) - strtotime("+$w day")
        //$sabado = date("Y-m-d", strtotime($lunes) + strtotime("+5D"));
        foreach ($aulas as $aula) {
            if ($vista == 'semanal') {
                $lunes[$i] = $ocupacionRepository->getOcupacionesDia(date("Y-m-d", strtotime($lun)), $aula->getId());
                $martes[$i] = $ocupacionRepository->getOcupacionesDia(date("Y-m-d", strtotime($lun) + strtotime("+1 day")), $aula->getId());
                $miercoles[$i] = $ocupacionRepository->getOcupacionesDia(date("Y-m-d", strtotime($lun) + strtotime("+2 day")), $aula->getId());
                $jueves[$i] = $ocupacionRepository->getOcupacionesDia(date("Y-m-d", strtotime($lun) + strtotime("+3 day")), $aula->getId());
                $viernes[$i] = $ocupacionRepository->getOcupacionesDia(date("Y-m-d", strtotime($lun) + strtotime("+4 day")), $aula->getId());
                $sabado[$i] = $ocupacionRepository->getOcupacionesDia(date("Y-m-d", strtotime($lun) + strtotime("+5 day")), $aula->getId());

                //$arrOcup[$i] = $ocupacionRepository->getOcupacionesSemana($lunes, $sabado, $aula->getId());
                //$diaSem = date('w', $key->getFecha()->format('U'));
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
        
        if ($vista == 'semanal') {
            return $this->render('ocupacion/semanal.html.twig', [
                'lunes' => $lunes,
                'martes' => $martes,
                'miercoles' => $miercoles,
                'jueves' => $jueves,
                'viernes' => $viernes,
                'sabado' => $sabado,
                'ocupacions' => $arrOcup,
                'dia1' => $lun,
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
