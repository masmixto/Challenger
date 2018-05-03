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
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => 'Name'
            ))
            ->add('email', EmailType::class, array(
                'label' => 'Email',
            ))
            ->add('subject', TextType::class, array(
                'label' => 'Topic',
            ))
            ->add('message', TextareaType::class, array(
                'label' => 'Message',
            ));

    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getBlockPrefix()
    {
        return 'app_bundle_contact_type';
    }
}
