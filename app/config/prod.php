<?php

// configure your app for the production environment

use Silex\Provider\MonologServiceProvider;

$app['twig.path'] = array(__DIR__.'/../templates');
$app['twig.options'] = array('cache' => __DIR__.'/../../var/cache/twig');
$app['twig.form.templates'] = array('bootstrap_3_layout.html.twig');

// A configurer lors de la mise en production

// $app['db.options'] = array(
//   'driver' => 'pdo_mysql',
//   'dbname' => 'evalefs',
//   'host' => 'localhost',
//   'user' => 'evalefs',
//   'password' => 'MOT_DE_PASSE',
//   'port' => '3306',
//   'charset' => 'utf8'
// );

$app->register(new MonologServiceProvider(), array(
  'monolog.logfile' => __DIR__.'/../../var/logs/evalefs_prod.log',
));
