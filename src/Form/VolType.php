<?php

namespace App\Form;

use App\Entity\Utilisateur;
use App\Entity\Vol;
use App\Entity\Avion;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VolType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('villeDepart')
            ->add('villeArrive')
            ->add('dateDepart', null, [
                'widget' => 'single_text',
            ])
            ->add('heureDepart', null, [
                'widget' => 'single_text',
            ])
            ->add('prixBillet')
            ->add('refAvion', EntityType::class, [
                'class' => Avion::class,
                'choice_label' => 'nom',
                'placeholder' => 'Sélectionnez un avion',
                'required' => true,
            ])
            ->add('refPilote', EntityType::class, [
                'class' => Utilisateur::class,
                'choices' => $options['pilotes'],
                'choice_label' => 'nom',
                'placeholder' => 'Choisissez un pilote',
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vol::class,
            'pilotes' => [],
        ]);
    }
}
