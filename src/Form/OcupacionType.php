<?php

namespace App\Form;

use App\Entity\Ocupacion;
use App\Entity\User;
use App\Entity\Aulas;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

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
        $profesores = $options['profesores'];
        $aulas = $options['aulas'];
        $builder
            ->add('fecha', DateType::class, ['widget' => 'single_text'])
            ->add('hora_inicio', TimeType::class, ['widget' => 'single_text'])
            ->add('hora_fin', TimeType::class, ['widget' => 'single_text'])
            //->add('id_aula')
            ->add('id_aula', EntityType::class, [
                'class' => Aulas::class,
                'placeholder' => '',
                'choices' => $aulas])
            ->add('id_catedra')
            ->add('comision', null, ['required' => false])
            ->add('id_area')
            ->add('rep_semanal')
            ->add('rep_fecha_fin', DateType::class, ['widget' => 'single_text'])
            ->add('observaciones', TextareaType::class, ['required' => false])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'placeholder' => '',
                'choices' => $profesores]);

        //$builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {});
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ocupacion::class,
            'profesores' => User::class,
            'aulas' => Aulas::class,
        ]);
    }
}
