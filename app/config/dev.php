<?php

use Silex\Provider\MonologServiceProvider;

// include the prod configuration
require __DIR__.'/prod.php';

// enable the debug mode
$app['debug'] = true;

$app['db.options'] = array(
  'driver' => 'pdo_mysql',
  'dbname' => 'evalefs',
  'host' => 'localhost',
  'user' => 'evalefs',
  'password' => 'evalefs',
  'port' => '3306',
  'charset' => 'utf8'
);

$app->register(new MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__.'/../../var/logs/silex_dev.log',
));

