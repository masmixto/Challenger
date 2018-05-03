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
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ChallengeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('amount', IntegerType::class, array(
                'label' => 'Amount'
            ))
            ->add('exercise', TextType::class, array(
                'label' => 'Exercise',
            ))
            ->add('time', IntegerType::class, array(
                'label' => 'Days',
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
