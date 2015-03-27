<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

//Request::setTrustedProxies(array('127.0.0.1'));

$app->get('/', function () use ($app) {

  $token = $app['security']->getToken();

  if (null !== $token) {
    $user = $token->getUser();
  }

  $sql = 'SELECT individu.cod_etu, lib_pr1_ind, lib_nom_pat_ind, mail_etu '.
    'FROM individu '.
    'INNER JOIN individu_etape ON individu_etape.cod_etu = individu.cod_etu '.
    'WHERE individu_etape.cod_etp = "9KFB1A" '.
    'ORDER BY individu.lib_nom_pat_ind '.
    'LIMIT 0 , 30';

  $data['etudiants'] = $app['db']->fetchAll($sql);

  if ($app['security']->isGranted('ROLE_ENSEIGNANT')) {
    $sql = 'SELECT etapes.lib_etp '.
      'FROM utilisateurs_etapes '.
      'INNER JOIN etapes ON utilisateurs_etapes.cod_etp = etapes.cod_etp '.
      'WHERE utilisateurs_etapes.login = "'.$user->getUsername().'"';
    $data['etapes'] = $app['db']->fetchAll($sql);
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
    $data = array(
        'name' => 'Your name',
        'email' => 'Your email',
    );

    $form = $app['form.factory']->createBuilder('evaluation', $data)->getForm();

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
