<?php

namespace App\Form;

use App\Entity\Patient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class DossierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        //->add('Patient', EntityType::class, [
           // 'class' => Patient::class,
            //'choice_label' => 'nom', // Remplacez 'nom' par le champ que vous souhaitez afficher dans le menu déroulant
           // 'placeholder' => 'Sélectionnez un patient', // Texte par défaut affiché dans le menu déroulant
            //'required' => true, // Définir à true si la sélection d'un patient est requise
        ///])
        ->add('resultatexamen')
        ->add('antecedentspersonnelles', ChoiceType::class, [
            'choices' => [
                'Hypertension' => 'hypertension artérielle',
                'Diabète' => 'diabete', 
                'Maladies cardiaques' => 'maladies_cardiaques', 
                'Allergies aux pénicillines' => 'allergies_penicillines', 
            ],
            'multiple' => true, // Pour le choix multiple
            'expanded' => true, // Afficher les choix sous forme de cases à cocher
        ])
        ->add('Ajouter', SubmitType::class);
        
        

            
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
