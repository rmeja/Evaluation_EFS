<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class EvaluationType extends AbstractType {

  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder
      ->add('name')
      ->add('email')
      ->add('gender', 'choice', array(
        'choices' => array(1 => 'male', 2 => 'female'),
        'expanded' => true,
        )
      )
    ;
  }

  public function getName()
  {
    return 'evaluation';
  }
}
