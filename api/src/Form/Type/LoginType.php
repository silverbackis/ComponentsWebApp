<?php

namespace App\Form\Type;

use App\Entity\LoginUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoginType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
            'data_class' => LoginUser::class,
            'method' => 'POST',
            'attr' => [
                'id' => 'login'
            ]
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', EmailType::class, [
                'attr' => [
                    'placeholder' => ''
                ],
                'label' => 'Email'
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Password'
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'is-large is-success is-fullwidth'
                ],
                'label' => 'Send'
            ])
        ;
    }

    public function getBlockPrefix()
    {
        return null;
    }
}
