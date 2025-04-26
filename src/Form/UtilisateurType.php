<?php

namespace App\Form;

use App\Entity\modele;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('password')
            ->add('nom')
            ->add('prenom')
            ->add('date_Naissance', null, [
                'widget' => 'single_text',
            ])
            ->add('ville')
            ->add('ref_modele', EntityType::class, [
                'class' => modele::class,
                'choice_label' => 'id',
            ])
            ->add('metier', ChoiceType::class, [
                'choices'  => [
                    'Client' => 'Client',
                    'Pilote' => 'Pilote',
                    'Hôtesse' => 'Hôtesse',
                    'Mécanicien' => 'Mécanicien',
                    'Contrôleur aérien' => 'Contrôleur aérien',
                ],
                'required' => true,
                'label' => 'Métier',
                'placeholder' => 'Choisissez un métier',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
