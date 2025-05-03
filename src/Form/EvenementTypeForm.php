<?php

namespace App\Form;

use App\Entity\Evenement;
use App\Entity\Organisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EvenementTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', null, [
                'label' => 'Titre de l\'événement',
            ])
            ->add('description', null, [
                'label' => 'Description',
            ])
            ->add('date', null, [
                'widget' => 'single_text',
                'label' => 'Date et heure',
            ])
            ->add('lieu', null, [
                'label' => 'Lieu',
            ])
            ->add('places_disponibles', null, [
                'label' => 'Nombre de places disponibles',
            ])
            ->add('organisateur', EntityType::class, [
                'class' => Organisateur::class,
                'choice_label' => 'nom', // Change en fonction de la propriété affichable
                'label' => 'Organisateur',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}
