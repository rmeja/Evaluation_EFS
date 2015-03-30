<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

//Request::setTrustedProxies(array('127.0.0.1'));

$app->get('/', function () use ($app) {

  $token = $app['security']->getToken();

  if (null !== $token) {
    $user = $token->getUser();
  }

  if ($app['security']->isGranted('ROLE_ENSEIGNANT')) {
    $sql_etape = 'SELECT etapes.lib_etp, etapes.cod_etp '.
        'FROM utilisateurs_etapes '.
        'INNER JOIN etapes ON utilisateurs_etapes.cod_etp = etapes.cod_etp '.
        'WHERE utilisateurs_etapes.login = "'.$user->getUsername().'"' .
        'ORDER BY lib_etp';
  } else {
    $sql_etape = 'SELECT lib_etp, cod_etp FROM etapes ORDER BY lib_etp';
  }

  $data['etapes'] = $app['db']->fetchAll($sql_etape);


  $sql_etudiant = 'SELECT individu.cod_etu, lib_pr1_ind, lib_nom_pat_ind, mail_etu '.
      'FROM individu '.
      'INNER JOIN individu_etape ON individu_etape.cod_etu = individu.cod_etu ';

  $etape = $app['request']->get('etape');
  $access = FALSE;

  if (isset($etape)) {
    foreach ($data['etapes'] as $item) {
      if (in_array($etape, $item)) {
        $access = TRUE;
      }
    }

    if ($access) {
      $sql_etudiant .= 'WHERE individu_etape.cod_etp = "'.$etape.'" ';
    } else {
      throw new \Exception('acces denied', 403);
    }
  }

  $sql_etudiant .= 'ORDER BY individu.lib_nom_pat_ind ';

  $data['etudiants'] = $app['db']->fetchAll($sql_etudiant);



  $name = $app['request']->get('name');
  if (isset($name)) {
    $app['monolog']->addInfo(sprintf("le nom est : '%s'", $name));
  }


  return $app['twig']->render('index.twig', $data);
})->bind('homepage');

$app->get('/login', function (Request $request) use ($app) {
  return $app['twig']->render('login.twig', array(
      'error'         => $app['security.last_error']($request),
      'last_username' => $app['session']->get('_security.last_username'),
  ));
})->bind('login');

//$app->match('/password', function (Request $request) use ($app) {
//  return (new \Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder())->encodePassword('boo', '');
//});

$app->match('/form', function (Request $request) use ($app) {
    // some default data for when the form is displayed the first time
    // script appel étudiant par cod_etu
   $cod_etu = $request->get('cod_etu');

   $sql = 'SELECT individu.cod_etu, lib_pr1_ind, lib_nom_pat_ind, mail_etu, '.
   'individu_etape.lib_web_vet, motif2 ' .
   'FROM individu '.
   'INNER JOIN individu_etape ON individu.cod_etu = individu_etape.cod_etu '.
   'AND individu.cod_etu = "'.$cod_etu.'" '.
   'LEFT JOIN evaluation ON individu.cod_etu = evaluation.cod_etu ';
   $data = $app['db']->fetchAssoc($sql);

    $form = $app['form.factory']->createBuilder('evaluation', $data)->getForm();

    $form->handleRequest($request);

    if ($form->isValid()) {
        $data = $form->getData();

        // do something with the data
        $cod_etu_eval = $request->get('cod_etu') ;
        $condition2 = $data['condition'] ;
        $motif2 = $data['motif2'] ;
        $data2 = $app ['db']->query('insert into evaluation (cod_etu, motif2) values("'.$cod_etu_eval.'", "'.$motif2.'") ');

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
