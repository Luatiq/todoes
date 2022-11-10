<?php

namespace App\Form;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('display', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'autofocus' => true,
                ],
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'required' => false,
            ])
            ->add('deadline', DateTimeType::class, [
                'html5' => true,
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control',
                    'onchange' => 'toggleHardDeadlineCheckbox(!!this.value)',
                ],
                'required' => false,
            ])
            ->add('isHardDeadline', CheckboxType::class, [
                'attr' => [
                ],
                'required' => false,
            ])
            ->add('concentrationLevelRequired', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'min' => 0,
                    'max' => 3,
                ],
                'required' => false,
                'empty_data' => 0,
            ])
        ;

        if (!$options['isOwnTask'] && $builder->getData()->getId()) {
            $builder->add('createdBy', EntityType::class, [
                'class' => User::class,
                'attr' => [
                    'class' => 'form-control'
                ],
                'disabled' => true,
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
            'isOwnTask' => true,
        ]);
    }
}