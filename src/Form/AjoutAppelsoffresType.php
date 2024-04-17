<?php

namespace App\Form;


use App\Entity\AppelOffre;
use App\Entity\Stocks;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AjoutAppelsoffresType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user')
            ->add('titre')
            ->add('description')
            ->add('prixInitial')
            ->add('dateFin')
            ->add('stocks', EntityType::class, [
                'class' => Stocks::class,
                'choice_label' => 'nom', // Changer 'nom' par le champ que vous souhaitez afficher
                'multiple' => true, // Si vous souhaitez permettre la sélection de plusieurs stocks
                'expanded' => true, // Si vous souhaitez afficher les options sous forme de cases à cocher plutôt que d'une liste déroulante
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->where('s.appelOffre IS NULL');
                },
                // Autres options selon vos besoins
            ])
            ->add('save',SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AppelOffre::class,
        ]);
    }
}
