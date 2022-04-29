<?php

namespace App\Form;

use App\Entity\Aswer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnswerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('text', TextareaType::class, [
                'label' => ' ',
                'attr' => [
                    'placeholder' => 'Введите ваш ответ сюда',
                    'class' => 'form-control',


                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Отправить на проверку',
                'attr' => [
                    'class' => 'btn btn-primary mb-2 tex col text-center',
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Aswer::class,
        ]);
    }
}
