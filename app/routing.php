<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

//Request::setTrustedProxies(array('127.0.0.1'));

$app->get('/', function () use ($app) {

  $sql = 'SELECT individu.cod_etu, lib_pr1_ind, lib_nom_pat_ind, mail_etu '.
    'FROM individu '.
    'INNER JOIN individu_etape ON individu_etape.cod_etu = individu.cod_etu '.
    'WHERE individu_etape.cod_etp = "9KFB1A" '.
    'ORDER BY individu.lib_nom_pat_ind '.
    'LIMIT 0 , 30';

  $data['etudiants'] = $app['db']->fetchAll($sql);

  return $app['twig']->render('index.twig', $data);
  //return $app['evaluation']->render('evaluation.twig', $data);
})->bind('homepage');


$app->get('/login', function (Request $request) use ($app) {
  return $app['twig']->render('login.twig', array(
      'error'         => $app['security.last_error']($request),
      'last_username' => $app['session']->get('_security.last_username'),
  ));
})->bind('login');

//appel form évaluation EFS
$app->get('/evaluation/{cod_etu}', function (Request $request, $cod_etu) use ($app) {

  $sql = 'SELECT individu.cod_etu, lib_pr1_ind, lib_nom_pat_ind, mail_etu, '.
   'individu_etape.lib_etp '.
   'FROM individu, individu_etape '.
   'WHERE individu.cod_etu = individu_etape.cod_etu '.
   'AND individu.cod_etu = "'.$cod_etu.'"' ;

   $data['etu_en_cours'] = $app['db']->fetchAll($sql);
   return $app['twig']->render('evaluation.twig', $data);


})->bind('evaluation');

$app->match('/form', function (Request $request) use ($app) {
    // some default data for when the form is displayed the first time
    $data = array(
        'name' => 'Your name',
        'email' => 'Your email',
    );

    $form = $app['form.factory']->createBuilder('form', $data)
        ->add('name')
        ->add('email')
        ->add('gender', 'choice', array(
            'choices' => array(1 => 'male', 2 => 'female'),
            'expanded' => true,
        ))
        ->getForm();

    $form->handleRequest($request);

    if ($form->isValid()) {
        $data = $form->getData();

        // do something with the data

        // redirect somewhere
        return $app->redirect('/');
    }

    // display the form
    return $app['twig']->render('form.twig', array('form' => $form->createView()));
});

$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    // 404.html, or 40x.html, or 4xx.html, or error.html
    $templates = array(
        'errors/'.$code.'.twig',
        'errors/'.substr($code, 0, 2).'x.twig',
        'errors/'.substr($code, 0, 1).'xx.twig',
        'errors/default.twig',
    );

    return new Response($app['twig']->resolveTemplate($templates)->render(array('code' => $code)), $code);
});
