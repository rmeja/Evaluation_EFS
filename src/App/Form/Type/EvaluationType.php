<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class EvaluationType extends AbstractType
{

  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder

      ->add('lib_nom_pat_ind', null, ['label' => 'Nom Patronymique', 'read_only' => 'true'])
      ->add('lib_nom_usu_ind', null, ['label' => 'Nom Usuel', 'read_only' => 'true'])
      ->add('lib_pr1_ind', null, ['label' => 'Prénom', 'read_only' => 'true'])
      ->add('lib_web_vet', null, ['label' => 'Etape', 'read_only' => 'true'])
      ->add('condition_de_travail', 'choice', array(
        'choices' => array(1 => 'condition remplie', 2 => 'condition non remplie'),
        'expanded' => true,
        )
      )
      ->add('motif_condition2', null, ['max_length' => '500'])

      ->add('condition_assiduite', 'choice', array(
        'choices' => array(1 => 'condition remplie', 2 => 'condition non remplie'),
        'expanded' => true,
        )
      )
      ->add('motif_condition3', null, ['max_length' => '500'])
    ->add('Valider', 'submit')
    ;
  }

  public function getName()
  {
    return 'evaluation';
  }
}
