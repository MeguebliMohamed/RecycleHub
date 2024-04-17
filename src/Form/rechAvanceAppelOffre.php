<?php
// src/Form/AppelOffreType.php

namespace App\Form;

use App\Entity\AppelOffre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class rechAvanceAppelOffre extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class)
            ->add('description', TextType::class)
            ->add('prixInitial', NumberType::class, [
                'scale' => 2, // nombre de décimales
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Recherche',
                'attr' => ['class' => 'btn btn-primary'],
            ])
            ->add('reset', ResetType::class, [ // Ajout du bouton de réinitialisation
                'label' => 'Réinitialiser',
                'attr' => ['class' => 'btn btn-danger'], // Classes CSS pour styliser le bouton
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([

        ]);
    }
}
