<?php

require_once __DIR__.'/../vendor/autoload.php';

ini_set('display_errors', 0);

$app = require __DIR__.'/../app/app.php';
require __DIR__.'/../app/config/prod.php';
require __DIR__.'/../app/routing.php';
$app->run();
