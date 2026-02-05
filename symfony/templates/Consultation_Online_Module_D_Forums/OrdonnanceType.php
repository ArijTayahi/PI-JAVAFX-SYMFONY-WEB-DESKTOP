<?php
// src/Form/OrdonnanceType.php

namespace App\Consultation_Online_Module_D_Forums\Form;

use App\Consultation_Online_Module_D_Entity\Entity\Ordonnance;
use App\Consultation_Online_Module_D_Entity\Entity\LigneOrdonnance;
use App\Consultation_Online_Module_D_Entity\Entity\Patient;
use App\Consultation_Online_Module_D_Entity\Entity\Medecin;
use App\Consultation_Online_Module_D_Entity\Entity\Consultation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use App\Form\LigneOrdonnanceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrdonnanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('consultation', EntityType::class, [
                'class' => Consultation::class,
                'choice_label' => function(Consultation $c) {
                    return 'Consultation du ' . $c->getDateDebut()->format('d/m/Y');
                },
                'label' => 'Consultation *',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('patient', EntityType::class, [
                'class' => Patient::class,
                'choice_label' => function(Patient $p) {
                    return $p->getPrenom() . ' ' . $p->getNom();
                },
                'label' => 'Patient *',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('medecin', EntityType::class, [
                'class' => Medecin::class,
                'choice_label' => function(Medecin $m) {
                    return 'Dr. ' . $m->getNom();
                },
                'label' => 'Médecin *',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('dateEmission', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date d\'émission *',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('dateValidite', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de validité *',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('diagnostic', TextareaType::class, [
                'label' => 'Diagnostic',
                'required' => false,
                'attr' => ['class' => 'form-control', 'rows' => 3],
            ])
            ->add('instructions', TextareaType::class, [
                'label' => 'Instructions',
                'required' => false,
                'attr' => ['class' => 'form-control', 'rows' => 3],
            ])
            ->add('lignes', CollectionType::class, [
                'entry_type' => LigneOrdonnanceType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => 'Médicaments',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ordonnance::class,
        ]);
    }
}