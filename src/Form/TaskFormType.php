<?php


namespace App\Form;


use App\Entity\Task;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('content', TextareaType::class)
            ->add('priority', ChoiceType::class, [
                'choices' => [
                    'Normalny' => 'normal',
                    'Średni' => 'medium',
                    'Wysoki' => 'important'
                ]
            ])
            ->add('deadline', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('users', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'login',
                'multiple' => true,
                'expanded' => true,
                'invalid_message' => 'Nie kombinuj'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
           'data_class' => Task::class
        ]);
    }


}