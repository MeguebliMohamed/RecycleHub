<?php

namespace App\Form;

use App\Entity\AppelOffre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AppelOffreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('titre')
            ->add('description')
            ->add('prixInitial')
            ->add('prixFinal')
            ->add('dateDebut')
            ->add('dateFin')
            ->add('etat')
            ->add('etatPayment')
            ->add('user');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AppelOffre::class,
        ]);
    }
}
