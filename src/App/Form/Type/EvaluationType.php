<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class EvaluationType extends AbstractType {

  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder
      ->add('lib_nom_pat_ind', null, array('read_only' => true))
      ->add('lib_nom_usu_ind', null, array('read_only' => true))
      ->add('lib_pr1_ind', null, array('read_only' => true))
      ->add('dat_nai_ind', null, array('read_only' => true))
      ->add('lib_etp', null, array('read_only' => true))
      ->add('condition1', 'choice', array(
        'choices' => array(
          'Condition remplie',
          'En attente',
          'Condition non remplie'
          ),
        'expanded' => true
        )
      )
      ->add('condition2', 'choice', array(
        'choices' => array(
          'Condition remplie',
          'Condition non remplie'
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
          'Condition remplie',
          'Condition non remplie'
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
          'Condition remplie',
          'Condition non remplie'
          ),
        'expanded' => true
        )
      )
      ->add('valider', 'submit', array(
        'attr' => array(
          'class' => 'btn-primary'
        )
      ))
    ;
  }

  public function getName()
  {
    return 'evaluation';
  }
}
