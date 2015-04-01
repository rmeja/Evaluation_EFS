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
      ->add('lib_pr1_ind', null, ['label' => 'Prénom', 'read_only' => 'true'])
      ->add('condition2', 'choice', array(
              'choices' => array(1 => 'condition remplie', 2 => 'condition non remplie'),
              'expanded' => true,
              )
            )
      ->add('motif2', null, ['label' => 'Motifs condition travail','max_length' => '500'])

    ->add('condition3', 'choice', array(
           'choices' => array(1 => 'condition remplie', 2 => 'condition non remplie'),
           'expanded' => true,
           )
         )
         ->add('motif3', 'textarea', ['label' => 'Motifs assiduité', 'max_length' => '500'])

       ->add('Valider', 'submit')
    ;
  }

  public function getName()
  {
    return 'evaluation';
  }
}
