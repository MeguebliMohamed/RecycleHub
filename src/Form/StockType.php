<?php

namespace App\Form;

use App\Entity\Stock;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type')
            ->add('nom')
            ->add('description')
            ->add('prixunit')
            ->add('quantite')
            ->add('unite')
            ->add('dateajout')
            ->add('etat')
            ->add('imageUrl')
            ->add('idappeloffre')
            ->add('latitude')
            ->add('longitude')
         ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Stock::class,
        ]);
    }
}
