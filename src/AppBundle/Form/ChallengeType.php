<?php

/**
 * Created by PhpStorm.
 * User: masmiix
 * Date: 09.01.18
 * Time: 10:07
 */
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ChallengeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Amount', NumberType::class, array(
                'label' => 'Ilość'
            ))
            ->add('Exercise', TextType::class, array(
                'label' => 'Ćwiczenie',
            ))
            ->add('Time', NumberType::class, array(
                'label' => 'Ilość dni',
            ));

    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getBlockPrefix()
    {
        return 'app_bundle_challenge_type';
    }
}
