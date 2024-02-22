<?php

namespace App\Form;

use App\Entity\Patient;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrdonnanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
            ->add('medecamentprescrit')
            ->add('adresse')
            ->add('renouvellement')
            ->add('dateprescription')
            // ->add('patient',EntityType::class,[
            //     'class'=>Patient::class,
            //     'choice_label'=>'nom'
            // ])
            // ->add('patient', EntityType::class, [
            //     'class' => Patient::class,
            //     'choice_label' => 'nom', // Nom de la propriété de l'entité Patient à afficher dans le champ
            //     'placeholder' => 'Sélectionner un patient', // Optionnel : texte à afficher comme option vide
            //     // Vous pouvez ajouter d'autres options selon vos besoins
            // ])
            ->add('Ajouter',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
