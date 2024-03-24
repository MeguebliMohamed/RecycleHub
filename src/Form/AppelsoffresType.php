<?php

namespace App\Form;

use App\Entity\Appelsoffres;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AppelsoffresType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('description')
            ->add('prixinitial')
            ->add('prixfinal')
            ->add('dateDebut')
            ->add('dateFin')
            ->add('etat')
            ->add('etatpayment')
            ->add('collecteur');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Appelsoffres::class,
        ]);
    }
}
