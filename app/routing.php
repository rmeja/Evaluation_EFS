<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

//Request::setTrustedProxies(array('127.0.0.1'));

$app->get('/', function () use ($app) {

  $token = $app['security']->getToken();

  if (null !== $token) {
    $user = $token->getUser();
  }

  $query_etape = $app['db']->createQueryBuilder();

  if ($app['security']->isGranted('ROLE_ENSEIGNANT')) {
    $query_etape
        ->select('e.lib_etp', 'e.cod_etp')
        ->from('utilisateurs_etapes', 'u_e')
        ->innerJoin('u_e', 'etapes', 'e', 'u_e.cod_etp = e.cod_etp')
        ->where('u_e.login = "'.$user->getUsername().'"')
        ->orderBy('lib_etp')
    ;

  } else {
    $query_etape
        ->select('lib_etp', 'cod_etp')
        ->from('etapes')
        ->orderBy('lib_etp')
    ;
  }

  $resultats_etapes = $query_etape->execute();
  $data['etapes'] = $resultats_etapes->fetchAll();

  $query_etudiants = $app['db']->createQueryBuilder();

  $query_etudiants
      ->select('*')
      ->from('individu', 'i')
      ->leftJoin('i', 'evaluation', 'e', 'i.cod_etu = e.cod_etu')
      ->innerJoin('i', 'individu_etape', 'i_e', 'i_e.cod_etu = i.cod_etu')
  ;

  $etape = $app['request']->get('etape');
  $access = FALSE;

  if (isset($etape)) {
    foreach ($data['etapes'] as $item) {
      if (in_array($etape, $item)) {
        $access = TRUE;
      }
    }

    if ($access) {
      $query_etudiants->orWhere('i_e.cod_etp = "'.$etape.'"');
    } else {
      throw new \Exception('Vous ne pouvez pas accéder à cette partie', 403);
    }
  } else {

    foreach ($data['etapes'] as $item) {
      $query_etudiants->orWhere('i_e.cod_etp = "'.$item['cod_etp'].'"');
    }
  }

  $resultats_etudiants = $query_etudiants->execute();
  $data['etudiants'] = $resultats_etudiants->fetchAll();

  $data['etape_active'] = '';

  foreach ($data['etapes'] as $item) {
    if (in_array($etape, $item)) {
      $data['etape_active'] = $item['lib_etp'];
    }
  }

  $nombre_etudiant_a_renseigner = 0;
  foreach ($data['etudiants'] as $etudiant) {
    if(empty($etudiant['datetime_modif'])) {
      $nombre_etudiant_a_renseigner++;
    }
  }
  $data['nombre_etudiant_total'] = count($data['etudiants']);
  $data['nombre_etudiant_a_renseigner'] = $nombre_etudiant_a_renseigner;


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

$app->match('/form/{id_etu}', function (Request $request, $id_etu) use ($app) {
  // some default data for when the form is displayed the first time

  $token = $app['security']->getToken();

  if (null !== $token) {
    $user = $token->getUser();
  }

  $query_etudiant = $app['db']->createQueryBuilder();

  $query_etudiant
    ->select('*')
    ->from('individu', 'i')
    ->leftJoin('i', 'evaluation', 'e', 'i.cod_etu=e.cod_etu')
    ->innerJoin('i', 'individu_etape', 'i_e', 'i_e.cod_etu=i.cod_etu')
    ->where('i.cod_etu = "'.$id_etu.'"')
  ;

  $resultat_etudiant = $query_etudiant->execute();
  $data = $resultat_etudiant->fetch();

  $date = new DateTime($data['dat_nai_ind']);

  $data['dat_nai_ind'] = date_format($date, 'd/m/Y');
  $form = $app['form.factory']->createBuilder('evaluation', $data)->getForm();

  $form->handleRequest($request);

  if ($form->isValid()) {
    $formData = $form->getData();

    $isNew = is_null($data['datetime_modif']);

    if ($formData['condition1']=='remplie' && $formData['condition2']=='remplie' && $formData['condition3']=='remplie') {
      $formData['directeur_choix'] = 'positif';
    }

    if ($isNew) {
      $query_insert_evaluation = $app['db']->createQueryBuilder();
      $query_insert_evaluation
        ->insert('evaluation')
          ->values(
            array(
              'cod_etu' => '?',
              'cod_etp' => '?',
              'cod_vrs_vet' => '?',
              'login_uti' => '?',
              'condition1' => '?',
              'condition2' => '?',
              'motif2' => '?',
              'condition3' => '?',
              'motif3' => '?',
              'directeur_choix' => '?',
              'datetime_modif' => '?'
            )
          )
          ->setParameter(0, $data['cod_etu'])
          ->setParameter(1, $data['cod_etp'])
          ->setParameter(2, $data['cod_vrs_vet'])
          ->setParameter(3, $user->getUsername())
          ->setParameter(4, $formData['condition1'])
          ->setParameter(5, $formData['condition2'])
          ->setParameter(6, $formData['motif2'])
          ->setParameter(7, $formData['condition3'])
          ->setParameter(8, $formData['motif3'])
          ->setParameter(9, $formData['directeur_choix'])
          ->setParameter(10, time())
      ;
      $query_insert_evaluation->execute();
    } else {
      $query_update_evaluation = $app['db']->createQueryBuilder();
      $query_update_evaluation
        ->update('evaluation')
        ->set('login_uti', '?')
        ->set('condition1', '?')
        ->set('condition2', '?')
        ->set('motif2', '?')
        ->set('condition3', '?')
        ->set('motif3', '?')
        ->set('directeur_choix', '?')
        ->set('datetime_modif', '?')
        ->setParameter(0, $user->getUsername())
        ->setParameter(1, $formData['condition1'])
        ->setParameter(2, $formData['condition2'])
        ->setParameter(3, $formData['motif2'])
        ->setParameter(4, $formData['condition3'])
        ->setParameter(5, $formData['motif3'])
        ->setParameter(6, $formData['directeur_choix'])
        ->setParameter(7, time())
        ->where('cod_etu = "'.$formData['cod_etu'].'"')
      ;
      $query_update_evaluation->execute();
    }

    // redirect somewhere
    return $app->redirect('/');
  }

  $data['user'] = $user->getUsername();

  // display the form
  return $app['twig']->render('form.twig', array(
    'form' => $form->createView(),
    'data' => $data
    )
  );
})->bind('form');;

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
