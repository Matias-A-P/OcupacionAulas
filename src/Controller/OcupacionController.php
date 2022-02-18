<?php

namespace App\Controller;

use App\Entity\Ocupacion;
use App\Form\OcupacionType;
use App\Repository\OcupacionRepository;
use App\Entity\Aulas;
use App\Entity\Edificios;
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
        if ($request->isMethod('GET')) {
            $dia = $request->query->get('dia', date('Y-m-d'));
            $vista = $request->query->get('vista', 'dia');
            $area = $request->query->get('area', 0);
            $edificio = $request->query->get('edificio', 0);
        } else {
            $dia = $request->request->get('dia', date('Y-m-d'));
            $vista = $request->request->get('vista', 'dia');
            $area = $request->request->get('area', 0);
            $edificio = $request->request->get('edificio', 0);
        }
        $session = $this->get('session');
        if ($edificio <= 0) {
            $edificio = $session->get('id_edificio');
        } else {
            $session->set('id_edificio', $edificio);
            $ed = $this->getDoctrine()->getRepository(Edificios::class)->find($edificio);
            $session->set('edificio', $ed->getEdificio());
        }

        $aulas = $this->getDoctrine()->getRepository(Aulas::class)->findBy(['id_edificio'=>$edificio]);
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
                $lunes[$i] = $ocupacionRepository->getOcupacionesDia($lun, $aula->getId(), $area, $edificio);
                if (empty($lunes[$i])) {
                    $ocup = new Ocupacion();
                    $ocup->setIdAula($aula);
                    $lunes[$i] = array($ocup);
                }
                $martes[$i] = $ocupacionRepository->getOcupacionesDia($mar, $aula->getId(), $area, $edificio);
                if (empty($martes[$i])) {
                    $ocup = new Ocupacion();
                    $ocup->setIdAula($aula);
                    $martes[$i] = array($ocup);
                }
                $miercoles[$i] = $ocupacionRepository->getOcupacionesDia($mie, $aula->getId(), $area, $edificio);
                if (empty($miercoles[$i])) {
                    $ocup = new Ocupacion();
                    $ocup->setIdAula($aula);
                    $miercoles[$i] = array($ocup);
                }
                $jueves[$i] = $ocupacionRepository->getOcupacionesDia($jue, $aula->getId(), $area, $edificio);
                if (empty($jueves[$i])) {
                    $ocup = new Ocupacion();
                    $ocup->setIdAula($aula);
                    $jueves[$i] = array($ocup);
                }
                $viernes[$i] = $ocupacionRepository->getOcupacionesDia($vie, $aula->getId(), $area, $edificio);
                if (empty($viernes[$i])) {
                    $ocup = new Ocupacion();
                    $ocup->setIdAula($aula);
                    $viernes[$i] = array($ocup);
                }
                $sabado[$i] = $ocupacionRepository->getOcupacionesDia($sab, $aula->getId(), $area, $edificio);
                if (empty($sabado[$i])) {
                    $ocup = new Ocupacion();
                    $ocup->setIdAula($aula);
                    $sabado[$i] = array($ocup);
                }
            } else {
                $arrOcup[$i] = $ocupacionRepository->getOcupacionesDia($dia, $aula->getId(), $area, $edificio);
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
                'area' => $area,
                'edificio' => $edificio,
            ]);
        } else {
            return $this->render('ocupacion/index.html.twig', [
                'ocupacions' => $arrOcup,
                'fecha' => $dia,
                'area' => $area,
                'edificio' => $edificio,
            ]);
        }
    }

    /**
     * @Route("/ocupacion/new", name="ocupacion_new", methods={"GET","POST"})
     * 
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(Request $request): Response
    {
        if ($request->isMethod('GET')) {
            $aula = $request->query->get('aula', 0);
            $dia = new \DateTime($request->query->get('dia', date('Y-m-d')));
            $hora = $request->query->get('hora', '14:00');
            $vista = $request->query->get('vista', 'dia');
        } else {
            $aula = $request->request->get('aula', 0);
            $dia = new \DateTime($request->request->get('dia', date('Y-m-d')));
            $hora = $request->request->get('hora', '14:00');
            $vista = $request->request->get('vista', 'dia');
        }
        $dia->setTime(0, 0, 0);
        $ocupacion = new Ocupacion();
        $ocupacion->setIdAula($this->getDoctrine()->getRepository(Aulas::class)->find($aula));
        $ocupacion->setFecha($dia);
        $ocupacion->setHoraInicio(new \DateTime($hora));
        $ocupacion->setHoraFin((new \DateTime($hora))->add(new DateInterval('PT1H')));
        $ocupacion->setRepFechaFin($dia);
        $form = $this->createForm(OcupacionType::class, $ocupacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sdia = date('Y-m-d', $dia->getTimestamp());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ocupacion);
            $entityManager->flush();
            // repetir
            if ($ocupacion->getRepSemanal()) {
                $this->getDoctrine()->getRepository(Ocupacion::class)->repetir($ocupacion);
            }

            return $this->redirectToRoute('ocupacion_index', ['dia' => $sdia, 'vista' => $vista], Response::HTTP_SEE_OTHER);
        }

        $fecha_padre = $ocupacion->getFecha();
        if ($ocupacion->getRepIdPadre() > 0) {
            $padre = $this->getDoctrine()->getRepository(Ocupacion::class)->find($ocupacion->getRepIdPadre());
            $fecha_padre = $padre->getFecha();
        }

        return $this->renderForm('ocupacion/_form_modal.html.twig', [
            'ocupacion' => $ocupacion,
            'form' => $form,
            'action' => $this->generateUrl('ocupacion_new'),
            'idOcup' => 0,
            'fecha_padre' => $fecha_padre,
            'vista' => $vista,
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

        $ocupacion = $this->getDoctrine()->getRepository(Ocupacion::class)->find($id);

        $form = $this->createForm(OcupacionType::class, $ocupacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $sdia = date('Y-m-d', $ocupacion->getFecha()->getTimestamp());

            $this->getDoctrine()->getRepository(Ocupacion::class)->borrarRepeticiones($ocupacion->getId());
            // repetir
            if ($ocupacion->getRepSemanal() && ($ocupacion->getRepIdPadre() <= 0)) {
                $this->getDoctrine()->getRepository(Ocupacion::class)->repetir($ocupacion);
            }
            return $this->redirectToRoute('ocupacion_index', ['dia' => $sdia], Response::HTTP_SEE_OTHER);
        }
        $fecha_padre = $ocupacion->getFecha();
        if ($ocupacion->getRepIdPadre() > 0) {
            $padre = $this->getDoctrine()->getRepository(Ocupacion::class)->find($ocupacion->getRepIdPadre());
            $fecha_padre = $padre->getFecha();
        }
        return $this->renderForm('ocupacion/_form_modal.html.twig', [
            'ocupacion' => $ocupacion,
            'form' => $form,
            'action' => $this->generateUrl('ocupacion_edit_modal', array('id' => $ocupacion->getId())),
            'idOcup' => $ocupacion->getId(),
            'fecha_padre' => $fecha_padre,
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
            $this->getDoctrine()->getRepository(Ocupacion::class)->borrarRepeticiones($ocupacion->getId());
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
