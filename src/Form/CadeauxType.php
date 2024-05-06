<?php

namespace App\Form;

use App\Entity\Cadeaux;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CadeauxType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('description')
            ->add('prix')
            ->add('stock')
            ->add('disponible')
            ->add('dateDisponibilite')
            ->add('categorie')
            ->add('imageUrl')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cadeaux::class,
        ]);
    }
}
