<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $passData = [
            'mapped' => false,
            'attr' => [
                'autocomplete' => 'new-password',
                'class' => 'form-control',
            ],
            'label_attr' => [
                'class' => 'mt-2'
            ],
            'constraints' => [
                new Length([
                    'min' => 6,
                    'minMessage' => 'Your password should be at least {{ limit }} characters',
                    // max length allowed by Symfony for security reasons
                    'max' => 4096,
                ]),
            ],
            'required' => false,
        ];

        if ($options['isRegistrationForm']) {
            $passData = array_merge_recursive($passData, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                ]
            ]);
        } else {
            $passData = array_merge($passData, [
                'help' => 'form.help.only_change_pass_when_filled_in',
            ]);
        }

        $builder
            ->add('display', TextType::class, [
                'required' => true,
                'label' => 'Display',
                'attr' => [
                    'class' => 'form-control',
                    'autofocus' => true,
                ],
                'label_attr' => [
                    'class' => 'mt-2'
                ],
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label_attr' => [
                    'class' => 'mt-2'
                ],
            ])
            ->add('plainPassword', PasswordType::class, $passData)
        ;

        if (!$options['isRegistrationForm']) {
            // Avatar stuff here
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'isRegistrationForm' => false,
        ]);
    }
}