<?php

namespace App\Form;

use App\Entity\Edificios;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
//use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class EdificiosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('edificio')
            ->add('Sede')
            ->add('facultad', ChoiceType::class, [
                'required' => true,
                'multiple' => false,
                'expanded' => true,
                'choices' => [
                    'Ciencias de la Vida y la Salud' => 'localhost', // aulasfcvs.uader.edu.ar
                    'Ciencia y Tecnología' => 'aulas.fcyt.uader.edu.ar',
                    'Ciencias de la Gestión' => 'aulas.fcg.uader.edu.ar'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Edificios::class,
        ]);
    }
}