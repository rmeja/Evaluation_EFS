<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class EvaluationType extends AbstractType {

  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder
      ->add('lib_nom_pat_ind', null, array('disabled' => true))
      ->add('lib_nom_usu_ind', null, array('disabled' => true))
      ->add('lib_pr1_ind', null, array('disabled' => true))
      ->add('dat_nai_ind', null, array('disabled' => true))
      ->add('lib_web_vet', null, array('disabled' => true))
      ->add('condition1', 'choice', array(
        'choices' => array(
          'remplie' => 'Condition remplie',
          'en attente' => 'En attente',
          'non remplie' => 'Condition non remplie'
          ),
        'expanded' => true
        )
      )
      ->add('condition2', 'choice', array(
        'choices' => array(
          'remplie' => 'Condition remplie',
          'non remplie' => 'Condition non remplie'
          ),
        'expanded' => true
        )
      )
      ->add('motif2', 'textarea', array(
        'label' => 'Motifs condition travail',
        'max_length' => '500'
        )
      )
      ->add('condition3', 'choice', array(
        'choices' => array(
          'remplie' => 'Condition remplie',
          'non remplie' => 'Condition non remplie'
          ),
        'expanded' => true,
        )
      )
      ->add('motif3', 'textarea', array(
        'label' => 'Motifs assiduité',
        'max_length' => '500'
        )
      )
      ->add('directeur_choix', 'choice', array(
        'choices' => array(
          'positif' => 'Avis positif',
          'negatif' => 'Avis négatif'
          ),
        'expanded' => true
        )
      )
      ->add('valider', 'submit')
    ;
  }

  public function getName()
  {
    return 'evaluation';
  }
}
