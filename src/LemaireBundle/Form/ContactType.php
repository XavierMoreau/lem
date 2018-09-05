<?php
// your-path-to-types/ContactType.php

namespace LemaireBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array('attr' => array('placeholder' => 'Saisissez votre nom'),
                'label' => "Nom",
                'attr' => ['class' => 'mail-input'],
                'constraints' => array(
                    new NotBlank(array("message" => "Merci d'indiquer votre nom")),
                )
            ))
            ->add('subject', TextType::class, array('attr' => array('placeholder' => "Saisissez l'objet"),
                 'label' => "Objet",
                'attr' => ['class' => 'mail-input'],
                'constraints' => array(
                    new NotBlank(array("message" => "Merci d'indiquer l'objet")),
                )
            ))
            ->add('email', EmailType::class, array('attr' => array('placeholder' => 'Saisissez votre adresse email'),
                'label' => "E-mail",
                'attr' => ['class' => 'mail-input'],
                'constraints' => array(
                    new NotBlank(array("message" => "Please provide a valid email")),
                    new Email(array("message" => "Email invalide")),
                )
            ))
            ->add('phone', TextType::class, array('attr' => array('placeholder' => 'Saisissez votre N° de Téléphone'),
                'label' => "Téléphone",
                'attr' => ['class' => 'mail-input'],
                'constraints' => array()
            ))
            ->add('message', TextareaType::class, array('attr' => array('placeholder' => 'Saisissez votre message'),
                'label' => "Message",
                'attr' => ['class' => 'mail-textarea'],
                'constraints' => array(
                    new NotBlank(array("message" => "Merci de renseigner votre message")),
                )
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'error_bubbling' => true
        ));
    }

    public function getName()
    {
        return 'contact_form';
    }
}
