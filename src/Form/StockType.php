<?php

namespace App\Form;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Stock;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', ChoiceType::class, [
                'label' => 'Type',
                'choices' => [
                    'Verre' => 'Verre',
                    'Papier' => 'Papier',
                    'Plastique' => 'Plastique',
                    'Textile' => 'Textile',
                    'Electronique' => 'Electronique',
                    'Métal' => 'Métal',
                    'Piles' => 'Piles',
                    'Carton' => 'Carton',
                    'Huile de cuisine' => 'Huile de cuisine',
                    'Organique' => 'Organique',
                    'Batteries' => 'Batteries',
                    'Verre de sécurité' => 'Verre de sécurité',
                    'Polystyrène' => 'Polystyrène',
                    'Téléphones portables' => 'Téléphones portables',
                    'Appareils électroménagers' => 'Appareils électroménagers',
                    'CD et DVD' => 'CD et DVD',
                    'Jouets en plastique' => 'Jouets en plastique',
                    'Télévisions et moniteurs' => 'Télévisions et moniteurs',
                    'Radiographies' => 'Radiographies',
                    'Câbles et fils' => 'Câbles et fils',
                ],
                'placeholder' => 'Choisir un type', // Optionnel : un libellé à afficher en premier comme choix par défaut
                'required' => true, // Optionnel : définir si le champ est obligatoire
                // Autres options
            ])
            ->add('nom')
            ->add('description')
            ->add('prixunit')
            ->add('quantite')

            ->add('unite', ChoiceType::class, [
                'label' => 'Unité',
                'choices' => [
                    'Kilogramme (kg)' => 'kg',
                    'Litre (l)' => 'l',
                    'Mètre (m)' => 'm',
                    'Mètre cube (m³)' => 'm³',
                ],
                'placeholder' => 'Choisir une unité', // Optionnel : un libellé à afficher en premier comme choix par défaut
                'required' => true, // Optionnel : définir si le champ est obligatoire
                // Autres options
            ])
            ->add('imageurl')
            ->add('Update',SubmitType::class);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([

        ]);
    }
}
