<?php

namespace App\Controller;

use App\Entity\Ocupacion;
use App\Form\OcupacionType;
use App\Repository\OcupacionRepository;
use App\Entity\Aulas;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use DateInterval;

class OcupacionController extends AbstractController
{
    /**
     * @Route("/ocupacion", name="ocupacion_index", methods={"GET","POST"})
     * 
     * @IsGranted("ROLE_USER")
     */
    public function index(Request $request, OcupacionRepository $ocupacionRepository): Response
    {
        //$dia->setTimezone(new TimeZones::getName("America/Buenos_Aires"));    
        //date_default_timezone_set("America/Buenos_Aires");
        if ($request->isMethod('GET')) {
            $dia = $request->query->get('dia', date('Y-m-d'));
            $vista = $request->query->get('vista', 'dia');
            $area = $request->query->get('area', 0);
        } else {
            $dia = $request->request->get('dia', date('Y-m-d'));
            $vista = $request->request->get('vista', 'dia');
            $area = $request->request->get('area', 0);
        }
        $aulas = $this->getDoctrine()->getRepository(Aulas::class)->findAll();
        $arrOcup = [];
        $i = 0;
        $w = date('w', strtotime($dia));
        $lun = date('Y-m-d', strtotime('-' . ($w - 1) . ' days', strtotime($dia)));
        $mar = date('Y-m-d', strtotime('-' . ($w - 2) . ' days', strtotime($dia)));
        $mie = date('Y-m-d', strtotime('-' . ($w - 3) . ' days', strtotime($dia)));
        $jue = date('Y-m-d', strtotime('-' . ($w - 4) . ' days', strtotime($dia)));
        $vie = date('Y-m-d', strtotime('-' . ($w - 5) . ' days', strtotime($dia)));
        $sab = date('Y-m-d', strtotime('-' . ($w - 6) . ' days', strtotime($dia)));

        foreach ($aulas as $aula) {
            if ($vista == 'semanal') {
                $lunes[$i] = $ocupacionRepository->getOcupacionesDia($lun, $aula->getId(), $area);
                if (empty($lunes[$i])) {
                    $ocup = new Ocupacion();
                    $ocup->setIdAula($aula);
                    $lunes[$i] = array($ocup);
                }
                $martes[$i] = $ocupacionRepository->getOcupacionesDia($mar, $aula->getId(), $area);
                if (empty($martes[$i])) {
                    $ocup = new Ocupacion();
                    $ocup->setIdAula($aula);
                    $martes[$i] = array($ocup);
                }
                $miercoles[$i] = $ocupacionRepository->getOcupacionesDia($mie, $aula->getId(), $area);
                if (empty($miercoles[$i])) {
                    $ocup = new Ocupacion();
                    $ocup->setIdAula($aula);
                    $miercoles[$i] = array($ocup);
                }
                $jueves[$i] = $ocupacionRepository->getOcupacionesDia($jue, $aula->getId(), $area);
                if (empty($jueves[$i])) {
                    $ocup = new Ocupacion();
                    $ocup->setIdAula($aula);
                    $jueves[$i] = array($ocup);
                }
                $viernes[$i] = $ocupacionRepository->getOcupacionesDia($vie, $aula->getId(), $area);
                if (empty($viernes[$i])) {
                    $ocup = new Ocupacion();
                    $ocup->setIdAula($aula);
                    $viernes[$i] = array($ocup);
                }
                $sabado[$i] = $ocupacionRepository->getOcupacionesDia($sab, $aula->getId(), $area);
                if (empty($sabado[$i])) {
                    $ocup = new Ocupacion();
                    $ocup->setIdAula($aula);
                    $sabado[$i] = array($ocup);
                }
            } else {
                $arrOcup[$i] = $ocupacionRepository->getOcupacionesDia($dia, $aula->getId(), $area);
                if (empty($arrOcup[$i])) {
                    $ocup = new Ocupacion();
                    $ocup->setIdAula($aula);
                    $arrOcup[$i] = array($ocup);
                }
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
                'fecha' => strtotime($lun),
                'aulas' => $aulas,
                'area' => $area
            ]);
        } else {
            return $this->render('ocupacion/index.html.twig', [
                'ocupacions' => $arrOcup,
                'fecha' => $dia,
                'area' => $area
            ]);
        }
    }

    /**
     * @Route("/ocupacion/new", name="ocupacion_new", methods={"GET","POST"})
     * 
     * @IsGranted("ROLE_ADMIN")
     */
    ///{aula}/{hora}   , int $aula=0, string $hora
    public function new(Request $request): Response
    {
        if ($request->isMethod('GET')) {
            $aula = $request->query->get('aula', 0);
            $dia = new \DateTime($request->query->get('dia', date('Y-m-d')));
            $hora = $request->query->get('hora', '14:00');
        } else {
            $aula = $request->request->get('aula', 0);
            $dia = new \DateTime($request->request->get('dia', date('Y-m-d')));
            $hora = $request->request->get('hora', '14:00');
        }
        $dia->setTime(0, 0, 0);
        $ocupacion = new Ocupacion();
        $ocupacion->setIdAula($this->getDoctrine()->getRepository(Aulas::class)->find($aula));
        $ocupacion->setFecha($dia);
        $ocupacion->setHoraInicio(new \DateTime($hora));
        $ocupacion->setHoraFin((new \DateTime($hora))->add(new DateInterval('PT1H')));
        $form = $this->createForm(OcupacionType::class, $ocupacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sdia = date('Y-m-d', $dia->getTimestamp()); // $ocupacion->getFecha()->getTimestamp()
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ocupacion);
            $entityManager->flush();

            // repetir
            if ($ocupacion->getRepSemanal()) {
                $fecha = $ocupacion->getFecha()->add(new DateInterval('P7D'));
                $ocupRep = new Ocupacion();
                $ocupRep->setIdAula($ocupacion->getIdAula());
                $ocupRep->setIdArea($ocupacion->getIdArea());
                $ocupRep->setIdCatedra($ocupacion->getIdCatedra());
                $ocupRep->setComision($ocupacion->getComision());
                $ocupRep->setFecha($fecha);
                $ocupRep->setHoraInicio($ocupacion->getHoraInicio());
                $ocupRep->setHoraFin($ocupacion->getHoraFin());
                $ocupRep->setRepIdPadre($ocupacion->getId());
                $ocupRep->setRepSemanal(true);
                $entityManager->persist($ocupRep);
                $entityManager->flush();
            }

            return $this->redirectToRoute('ocupacion_index', ['dia' => $sdia], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ocupacion/_form_modal.html.twig', [
            'ocupacion' => $ocupacion,
            'form' => $form,
            'action' => $this->generateUrl('ocupacion_new'),
            'idOcup' => 0
        ]);
    }

    /**
     * @Route("/ocupacion/{id}", name="ocupacion_show", methods={"GET"})
     * 
     * @IsGranted("ROLE_USER")
     */
    public function show(Ocupacion $ocupacion): Response
    {
        return $this->render('ocupacion/show.html.twig', [
            'ocupacion' => $ocupacion,
        ]);
    }

    /**
     * @Route("/ocupacion/{id}/edit", name="ocupacion_edit", methods={"GET","POST"})
     * 
     * @IsGranted("ROLE_ADMIN")
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
     * @Route("/ocupacion/edit_modal/{id}", name="ocupacion_edit_modal", methods={"GET","POST"})
     * 
     * @IsGranted("ROLE_ADMIN")
     */
    public function editModal(Request $request, int $id): Response
    {

        $ocupacion = $this->getDoctrine()->getRepository(Ocupacion::class)->find($id); // $request->query->get('id',15)

        $form = $this->createForm(OcupacionType::class, $ocupacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $sdia = date('Y-m-d', $ocupacion->getFecha()->getTimestamp());
            return $this->redirectToRoute('ocupacion_index', ['dia' => $sdia], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ocupacion/_form_modal.html.twig', [
            'ocupacion' => $ocupacion,
            'form' => $form,
            'action' => $this->generateUrl('ocupacion_edit_modal', array('id' => $ocupacion->getId())),
            'idOcup' => $ocupacion->getId()
        ]);
    }

    /**
     * @Route("/ocupacion/{id}", name="ocupacion_delete", methods={"POST"})
     * 
     * @IsGranted("ROLE_ADMIN")
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

    /**
     * @Route("/ocupado", methods={"POST"})
     */
    public function ocupado(Request $request): Response
    {
        $aula = $request->request->get('aula', 0);
        $dia = date('Y-m-d', strtotime($request->request->get('dia', '1970-01-02')));
        $hi = $request->request->get('hi', '00:00') . ':00';
        $hf = $request->request->get('hf', '00:00') . ':00';
        $id = $request->request->get('id', 0);
        $ocup = $this->getDoctrine()->getRepository(Ocupacion::class)->horarioOcupado($aula, $dia, $hi, $hf, $id);
        return new JsonResponse(json_encode($ocup));
    }
}
