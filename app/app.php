<?php

use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\SecurityServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use App\Form\Type\EvaluationType;

$app = new Application();
$app->register(new TwigServiceProvider());
$app->register(new SecurityServiceProvider());
$app->register(new SessionServiceProvider());
$app->register(new UrlGeneratorServiceProvider());
$app->register(new FormServiceProvider());
$app->register(new TranslationServiceProvider());
$app->register(new ValidatorServiceProvider());
$app->register(new DoctrineServiceProvider());

// Toutes les urls sont sécurisés sauf la page '/login'
$app['security.firewalls'] = array(
  'login' => array(
    'pattern' => '^/login$',
  ),
  'secured' => array(
    'pattern' => '^.*$',
    'form' => array(
      'login_path' => '/login',
      'check_path' => '/login_check'
    ),
    'logout' => array('logout_path' => '/logout'),
    'users' => array(
      // Password = foo
      'admin' => array('ROLE_ADMIN', '5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg=='),
    ),
  ),
);

$app['twig'] = $app->extend('twig', function ($twig, $app) {
  // add custom globals, filters, tags, ...

  $twig->addFunction(new \Twig_SimpleFunction('asset', function ($asset) use ($app) {
    return $app['request_stack']->getMasterRequest()->getBasepath().'/'.$asset;
  }));

  $twig->addFunction(new \Twig_SimpleFunction('path', function($url) use ($app) {
    return $app['url_generator']->generate($url);
  }));

  return $twig;
});

$app['twig.form.templates'] = array('bootstrap_3_layout.html.twig');

$app['form.types'] = $app->share($app->extend('form.types', function ($types) use ($app) {
  $types[] = new EvaluationType();

  return $types;
}));

return $app;
