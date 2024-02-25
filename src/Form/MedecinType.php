<?php

namespace App\Form;

use App\Entity\Medecin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class MedecinType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cin')
            ->add('nom')
            ->add('prenom')
            ->add('genre', ChoiceType::class, [
                'choices' => [
                    'Femme' => 'F',
                    'Homme' => 'H',
                ],
                'expanded' => true, // Afficher les choix comme des boutons radio
                'multiple' => false, // Permettre la sélection d'un seul choix
            ])
            ->add('datenaissance', DateType::class, [
                'widget' => 'single_text', // Afficher en tant qu'entrée texte unique
                'format' => 'yyyy-MM-dd', // Définir le format de date souhaité
            ])
            ->add('numtel')
            ->add('email')
            
            ->add('password', PasswordType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Password', 'id' => 'password-input'],
            ])
            ->add('specialite')
            ->add('etat', ChoiceType::class, [
                'choices' => [
                    'Disponible' => true,
                    'Non disponible' => false,
                ],
                'expanded' => true,
                'multiple' => false,
                'label' => 'Etat', // Ajoutez une étiquette pour le champ interlock
            ])
            ->add('interlock', ChoiceType::class, [
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'expanded' => true,
                'multiple' => false,
                'label' => 'Interlock', // Ajoutez une étiquette pour le champ interlock
            ])
            ->add('Ajouter', SubmitType::class)
            ->add('Annuler', SubmitType::class);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Medecin::class,
        ]);
    }
}
