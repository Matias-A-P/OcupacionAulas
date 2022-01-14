<?php

namespace App\Form;

use App\Entity\Ocupacion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\SubmitButtonTypeInterface;

class OcupacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fecha', DateType::class, ['widget' => 'single_text'])
            ->add('hora_inicio', TimeType::class, ['widget' => 'single_text'])
            ->add('hora_fin', TimeType::class, ['widget' => 'single_text'])
            ->add('id_aula')
            ->add('id_catedra')
            ->add('comision')
            //->add('save', SubmitType::class, ['attr' => ['label' => 'xGuardar']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ocupacion::class,
        ]);
    }


}
