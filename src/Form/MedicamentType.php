<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Medicament;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;


class MedicamentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('categorie',EntityType::class, [
            'class' => Categorie::class,
            'choice_label' => 'nom_cat', // Remplacez 'name' par le champ que vous voulez afficher dans le menu déroulant
                ])
            ->add('ref_med')
            ->add('nom_med')
            ->add('date_amm', DateType::class , [
                'widget' => 'single_text',
                'html5' => true,
            
                'data' => new \DateTime(), 
            
            ])
            ->add('date_expiration', DateType::class , [
                'widget' => 'single_text',
                'html5' => true,
            
                'data' => new \DateTime(), 
            
            ])
            ->add('Qte')
            ->add('description')
            ->add('etat', ChoiceType::class,['choices' => [
                'En stock' => 'En stock',
                'Epuisé' => 'Epuisé',
               
            ],])
            ->add('Ajouter',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Medicament::class,
        ]);
    }
}
