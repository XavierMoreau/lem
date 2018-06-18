<?php

namespace LemaireBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType; 


class CarType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('modele', EntityType::class, array(
                    'class' => 'LemaireBundle:Modele',
                    'choice_label' => 'name',
                    'multiple' => false))
                ->add('serie')
                ->add('motorisation')
                ->add('energie',EntityType::class, array(
                    'class' => 'LemaireBundle:Energie',
                    'choice_label' => 'name',
                    'multiple' => false))
                ->add('cvfiscaux')
                ->add('description')
                ->add('annee')
                ->add('kms')
                ->add('type',EntityType::class, array(
                    'class' => 'LemaireBundle:Type',
                    'choice_label' => 'name',
                    'multiple' => false))
                ->add('options',EntityType::class, array(
                    'class' => 'LemaireBundle:Options',
                    'choice_label' => 'name',
                    'multiple' => true,
                    'expanded' => true))
                ->add('couleur')
                ->add('boitevitesse')
                ->add('portes')
                ->add('prixdestock')
                ->add('prixgarantie')
                ->add('vendu')
                ->add('promotion')
                ->add('active');


    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'LemaireBundle\Entity\Car'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'lemairebundle_car';
    }


}
