<?php

namespace App\Form;

use App\Entity\Reclamation;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('reclaimType', ChoiceType::class, [
                'choices' => [
                    'Problèmes techniques' => 'Problèmes techniques',
                    'Problèmes de collecte' => 'Problèmes de collecte',
                    'Problèmes service client' => 'Problèmes service client',
                    'Suggestion d\'amélioration' => 'Suggestion d\'amélioration',
                ],
                'label' => 'Type de réclamation'
            ])
            ->add('description')
            ->add('status', TextType::class, [
                'mapped' => false,
            ])
            ->add('submissionDate', DateTimeType::class, [
                'data' => new \DateTime(),
                'attr' => ['readonly' => true],
            ])
            ->add('updateDate')
            ->add('user', UserType::class, [
                'mapped' => false,
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
        ]);
    }
}
