<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class EvaluationType extends AbstractType {

  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder
      ->add('lib_nom_pat_ind')
      ->add('lib_pr1_ind')
      ->add('radio_buttons', 'choice', array(
                'label'        => 'Radio buttons',
                'choices'      => array('1' => 'condition remplie', '2' => 'condition non remplie'),
        )
      )
      ->add('motif2')
    ->add('Valider', 'submit')
    ;


  }

  public function getName()
  {
    return 'evaluation';
  }
}
