<?php
// src/Form/ConsultationType.php

namespace App\Consultation_Online_Module_D_Forums\Form;

use App\Consultation_Online_Module_D_Entity\Entity\Consultation;
use App\Consultation_Online_Module_D_Entity\Entity\Patient;
use App\Consultation_Online_Module_D_Entity\Entity\Medecin;
use App\Consultation_Online_Module_D_Entity\Entity\RendezVous;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConsultationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('patient', EntityType::class, [
                'class' => Patient::class,
                'choice_label' => function(Patient $patient) {
                    return $patient->getPrenom() . ' ' . $patient->getNom();
                },
                'placeholder' => '-- Sélectionner un patient --',
                'label' => 'Patient *',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('medecin', EntityType::class, [
                'class' => Medecin::class,
                'choice_label' => function(Medecin $medecin) {
                    return 'Dr. ' . $medecin->getNom() . ' - ' . $medecin->getSpecialite();
                },
                'placeholder' => '-- Sélectionner un médecin --',
                'label' => 'Médecin *',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('rendezVous', EntityType::class, [
                'class' => RendezVous::class,
                'required' => false,
                'placeholder' => '-- Optionnel --',
                'label' => 'Rendez-vous associé',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('dateDebut', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date et heure de début *',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Consultation Vidéo' => 'VIDEO',
                    'Consultation au Cabinet' => 'CABINET',
                    'Consultation Audio' => 'AUDIO',
                ],
                'label' => 'Type de consultation *',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('motif', TextareaType::class, [
                'label' => 'Motif de la consultation',
                'required' => false,
                'attr' => ['class' => 'form-control', 'rows' => 3],
            ])
            ->add('symptomes', TextareaType::class, [
                'label' => 'Symptômes',
                'required' => false,
                'attr' => ['class' => 'form-control', 'rows' => 3],
            ])
            ->add('diagnostic', TextareaType::class, [
                'label' => 'Diagnostic',
                'required' => false,
                'attr' => ['class' => 'form-control', 'rows' => 4],
            ])
            ->add('notesConsultation', TextareaType::class, [
                'label' => 'Notes',
                'required' => false,
                'attr' => ['class' => 'form-control', 'rows' => 4],
            ])
            ->add('montant', NumberType::class, [
                'label' => 'Montant (TND)',
                'required' => false,
                'attr' => ['class' => 'form-control', 'step' => '0.01'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Consultation::class,
        ]);
    }
}