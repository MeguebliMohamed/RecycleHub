<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username')
           // ->add('roles')
            ->add('plainPassword' ,TextType::class ,[
                'mapped' =>false
           ])
           ->add('prenom')
            ->add('adresse')
            ->add('cin')
            ->add('telephone')
            //->add('NbrePoint')
            //->add('image_name')
            //->add('updated_at')
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Collecteur' => 'ROLE_COLLECTEUR',
                    'Sociéter' => 'ROLE_SOCIETE',
                ],
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('email')
            ->add('nom')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
