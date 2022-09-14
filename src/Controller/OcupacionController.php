<?php

namespace App\Controller;

use App\Entity\Ocupacion;
use App\Form\OcupacionType;
use App\Repository\OcupacionRepository;
use App\Entity\Aulas;
use App\Entity\Edificios;
use App\Entity\Areas;
use App\Entity\Catedras;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use DateInterval;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\Persistence\ManagerRegistry;

class OcupacionController extends AbstractController
{
    public function __construct(private ManagerRegistry $doctrine)
    {
    }

    /**
     * @Route("/ocupacion", name="ocupacion_index", methods={"GET","POST"})
     * 
     */
    public function index(Request $request, OcupacionRepository $ocupacionRepository, SessionInterface $session): Response
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
        if ($edificio <= 0) {
            $edificio = $session->get('id_edificio');
        } else {
            $session->set('id_edificio', $edificio);
            $ed = $this->doctrine->getRepository(Edificios::class)->find($edificio);
            $session->set('edificio', $ed->getEdificio());
        }
        $i = 0;
        $w = date('w', strtotime($dia));
        $lun = date('Y-m-d', strtotime('-' . ($w - 1) . ' days', strtotime($dia)));
        $mar = date('Y-m-d', strtotime('-' . ($w - 2) . ' days', strtotime($dia)));
        $mie = date('Y-m-d', strtotime('-' . ($w - 3) . ' days', strtotime($dia)));
        $jue = date('Y-m-d', strtotime('-' . ($w - 4) . ' days', strtotime($dia)));
        $vie = date('Y-m-d', strtotime('-' . ($w - 5) . ' days', strtotime($dia)));
        $sab = date('Y-m-d', strtotime('-' . ($w - 6) . ' days', strtotime($dia)));
        $aulas = $this->doctrine->getRepository(Aulas::class)->findBy(['id_edificio' => $edificio]);
        $arrOcup = [];
        if ($vista == 'horas') {
            $arrOcup[0] = $ocupacionRepository->getOcupacionesDia($dia, 0, $area, $edificio);
        } else {
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
        } elseif ($vista == 'dia') {
            return $this->render('ocupacion/index.html.twig', [
                'ocupacions' => $arrOcup,
                'fecha' => $dia,
                'area' => $area,
                'edificio' => $edificio,
            ]);
        } else {
            return $this->render('ocupacion/horas.html.twig', [
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
            $area = $request->query->get('area', 0);
            $activ = $request->query->get('activ', 0);
        } else {
            $aula = $request->request->get('aula', 0);
            $dia = new \DateTime($request->request->get('dia', date('Y-m-d')));
            $hora = $request->request->get('hora', '14:00');
            $vista = $request->request->get('vista', 'dia');
            $area = $request->request->get('area', 0);
            $activ = $request->request->get('activ', 0);
        }
        $dia->setTime(0, 0, 0);
        $ocupacion = new Ocupacion();
        // valores por defecto pasados desde el template
        $ocupacion->setIdAula($this->doctrine->getRepository(Aulas::class)->find($aula));
        $ocupacion->setFecha($dia);
        $ocupacion->setHoraInicio(new \DateTime($hora));
        $ocupacion->setHoraFin((new \DateTime($hora))->add(new DateInterval('PT1H')));
        $ocupacion->setRepFechaFin($dia);
        if ($area > 0) {
            $ocupacion->setIdArea($this->doctrine->getRepository(Areas::class)->find($area));
        }
        if ($activ > 0) {
            $ocupacion->setIdCatedra($this->doctrine->getRepository(Catedras::class)->find($activ));
        }

        $profesores = $this->doctrine->getRepository(User::class)->findProfesores();
        $form = $this->createForm(OcupacionType::class, $ocupacion, ['profesores' => $profesores]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sdia = date('Y-m-d', $ocupacion->getFecha()->getTimestamp());
            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($ocupacion);
            $entityManager->flush();
            // repetir
            if ($ocupacion->getRepSemanal()) {
                $this->doctrine->getRepository(Ocupacion::class)->repetir($ocupacion);
            }

            return $this->redirectToRoute('ocupacion_index', ['dia' => $sdia, 'vista' => $vista], Response::HTTP_SEE_OTHER);
        }
        // si es repetición, buscar la inicial
        $fecha_padre = $ocupacion->getFecha();
        if ($ocupacion->getRepIdPadre() > 0) {
            $padre = $this->doctrine->getRepository(Ocupacion::class)->find($ocupacion->getRepIdPadre());
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
            $this->doctrine->getManager()->flush();

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

        $ocupacion = $this->doctrine->getRepository(Ocupacion::class)->find($id);

        $profesores = $this->doctrine->getRepository(User::class)->findProfesores();
        $form = $this->createForm(OcupacionType::class, $ocupacion, ['profesores' => $profesores]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->doctrine->getManager()->flush();
            $sdia = date('Y-m-d', $ocupacion->getFecha()->getTimestamp());

            $this->doctrine->getRepository(Ocupacion::class)->borrarRepeticiones($ocupacion->getId());
            // repetir
            if ($ocupacion->getRepSemanal() && ($ocupacion->getRepIdPadre() <= 0)) {
                $this->doctrine->getRepository(Ocupacion::class)->repetir($ocupacion);
            }
            return $this->redirectToRoute('ocupacion_index', ['dia' => $sdia], Response::HTTP_SEE_OTHER);
        }
        $fecha_padre = $ocupacion->getFecha();
        if ($ocupacion->getRepIdPadre() > 0) {
            $padre = $this->doctrine->getRepository(Ocupacion::class)->find($ocupacion->getRepIdPadre());
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
            $this->doctrine->getRepository(Ocupacion::class)->borrarRepeticiones($ocupacion->getId());
            $entityManager = $this->doctrine->getManager();
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
        $ac = $request->request->get('ac', 0);
        $co = $request->request->get('co', 0);
        $us = $request->request->get('us', 0);
        $ocup = $this->doctrine->getRepository(Ocupacion::class)->horarioOcupado($aula, $dia, $hi, $hf, $id, $ac, $co, $us);
        return new JsonResponse(json_encode($ocup));
    }
}
