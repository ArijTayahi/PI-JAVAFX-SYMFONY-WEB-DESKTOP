<?php
// src/Form/LigneOrdonnanceType.php

namespace App\Form;

use App\Consultation_Online_Module_D_Entity\Entity\LigneOrdonnance;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LigneOrdonnanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomMedicament', TextType::class, [
                'label' => 'Médicament *',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('dosage', TextType::class, [
                'label' => 'Dosage *',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Ex: 500mg'],
            ])
            ->add('forme', ChoiceType::class, [
                'choices' => [
                    'Comprimé' => 'COMPRIME',
                    'Gélule' => 'GELULE',
                    'Sirop' => 'SIROP',
                    'Ampoule' => 'AMPOULE',
                    'Pommade' => 'POMMADE',
                    'Crème' => 'CREME',
                    'Gouttes' => 'GOUTTES',
                    'Suppositoire' => 'SUPPOSITOIRE',
                ],
                'label' => 'Forme *',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('quantite', IntegerType::class, [
                'label' => 'Quantité *',
                'attr' => ['class' => 'form-control', 'min' => 1],
            ])
            ->add('frequence', TextType::class, [
                'label' => 'Fréquence *',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Ex: 3 fois par jour'],
            ])
            ->add('dureeTraitement', IntegerType::class, [
                'label' => 'Durée (jours) *',
                'attr' => ['class' => 'form-control', 'min' => 1],
            ])
            ->add('instructions', TextareaType::class, [
                'label' => 'Instructions',
                'required' => false,
                'attr' => ['class' => 'form-control', 'rows' => 2],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LigneOrdonnance::class,
        ]);
    }
}