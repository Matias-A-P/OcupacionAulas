<?php

namespace App\Form;

use App\Entity\Ocupacion;
use App\Entity\Areas;
use App\Entity\Catedras;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Doctrine\ORM\EntityManagerInterface;


class OcupacionType extends AbstractType
{
    private $em;
    
    /**
     * 
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fecha', DateType::class, ['widget' => 'single_text'])
            ->add('hora_inicio', TimeType::class, ['widget' => 'single_text'])
            ->add('hora_fin', TimeType::class, ['widget' => 'single_text'])
            ->add('id_aula')
            // ->add('id_catedra')
            ->add('comision')
            // ->add('id_area')
        ;
        $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetData'));
        $builder->addEventListener(FormEvents::PRE_SUBMIT, array($this, 'onPreSubmit'));

        // $builder->addEventListener(
        //     FormEvents::PRE_SET_DATA,
        //     function (FormEvent $event) {
        //         $form = $event->getForm();
        //         $data = $event->getData();

        //         $area = $data->getIdArea();
        //         $catedras = null === $area ? [] : $area->getCatedras();

        //         $form->add('id_catedra', EntityType::class, [
        //             'class' => Catedras::class,
        //             'placeholder' => '',
        //             'choices' => $catedras,
        //         ]);
        //     }
        // );
    }

    protected function addElements(FormInterface $form, Areas $area = null) {
        $form->add('id_area', EntityType::class, array(
            'required' => true,
            'data' => $area,
            'placeholder' => 'Area...',
            'class' => Areas::class
        ));
        
        $catedras = array();
        
        if ($area) {
            $repoCatedras = $this->em->getRepository('AppBundle:Catedras');
            
            $catedras = $repoCatedras->createQueryBuilder("q")
                ->where("q.id_area = :areaid")
                ->setParameter("areaid", $area->getId())
                ->getQuery()
                ->getResult();
        };
        
        $form->add('id_catedra', EntityType::class, array(
            'required' => true,
            'placeholder' => 'Catedra...',
            'class' => Catedras::class,
            'choices' => $catedras
        ));
    }
    
    function onPreSubmit(FormEvent $event) {
        $form = $event->getForm();
        $data = $event->getData();
        
        $city = $this->em->getRepository(Areas::class)->find($data['area']);
        
        $this->addElements($form, $city);
    }

    function onPreSetData(FormEvent $event) {
        $ocup = $event->getData();
        $form = $event->getForm();

        $area = $ocup->getIdArea() ? $ocup->getIdArea() : null;
        
        $this->addElements($form, $area);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ocupacion::class,
        ]);
    }


}
