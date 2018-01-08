<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'placeholder' => 'Your Name'
                ],
                'label' => 'Your name',
                'constraints' => [
                    new NotBlank([
                        "message" => "Please provide your name"
                    ]),
                ]
            ])
            ->add('subject', ChoiceType::class, [
                'attr' => [
                    'placeholder' => 'Subject'
                ],
                'label' => 'Regarding',
                'choices' => [
                    'Please select' => '',
                    'General enquiry' => 'enquiry',
                    'Anything else' => 'other',
                    'Invalid option' => '-'
                ],
                // 'choices_as_values' => true,
                'choice_attr' => function($val, $key, $index) {
                    return $val==='' ? ['disabled' => ''] : [];
                },
                'constraints' => [
                    new NotBlank([
                        "message" => "Please select what the message is regarding"
                    ]),
                    new Length([
                        "min" => 2,
                        "minMessage" => "The option selected is invalid"
                    ])
                ]
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'placeholder' => 'Your email address'
                ],
                'label' => 'Your Email',
                'constraints' => [
                    new NotBlank([
                        "message" => "Please provide a valid email"
                    ]),
                    new Email([
                        "message" => "Your email doesn't seems to be valid"
                    ]),
                ]
            ])
            ->add('message', TextareaType::class, [
                'attr' => [
                    'placeholder' => 'Your message here'
                ],
                'label' => 'Message',
                'constraints' => [
                    new NotBlank([
                        "message" => "Please provide a message here"
                    ])
                ]
            ])
            ->add('developer', ChoiceType::class, array(
                'label'    => 'Are you a developer?',
                'choices' => [
                    'Yes' => 'yes',
                    'No' => 'no'
                ],
                'choice_attr' => function() {
                    return ['class' => 'custom'];
                },
                'expanded' => true,
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        "message" => "Please select if you area a developer"
                    ])
                ]
            ))
            ->add('randomCheckbox', CheckboxType::class, array(
                'attr' => [
                    'class' => 'custom'
                ],
                'label'    => 'To check or not to check? <b>That</b> is a question',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        "message" => "The correct answer to the question is to check, it is required"
                    ])
                ]
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'error_bubbling' => true
        ));
    }
}