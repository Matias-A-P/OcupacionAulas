<?php

namespace App\Form;

use App\Entity\User;
use Doctrine\DBAL\Types\StringType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

//use Symfony\Component\Form\CallbackTransformer;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre')
            ->add('dni')
            ->add('email')
            ->add('roles', ChoiceType::class, [
                'required' => true,
                'multiple' => true,
                'expanded' => true,
                'choices'  => [
                    'Usuario' => 'ROLE_USER',
                    'Profesor' => 'ROLE_PROF',
                    'Admin' => 'ROLE_ADMIN',
                    'Root' => 'ROLE_ROOT',
                ],
            ]);
        if ($options['nuevo']) {
            $builder->add('password', PasswordType::class, ['label' => 'Clave']);
        } else {
            $builder
                ->add('edit_pass', CheckboxType::class, ['required' => false, 'value' => false, 'mapped' => false, 'label' => 'Cambiar Clave'])
                ->add('plain_pass', PasswordType::class, ['required' => false, 'mapped' => false, 'label' => 'Nueva Clave']);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'nuevo' => true,
        ]);
    }
}
