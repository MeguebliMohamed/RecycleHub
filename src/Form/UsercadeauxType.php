<?php

namespace App\Form;

use App\Entity\Usercadeaux;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UsercadeauxType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantiteAchetee')
            ->add('dateAchat')
            ->add('utilisateur')
            ->add('cadeau')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Usercadeaux::class,
        ]);
    }
}
