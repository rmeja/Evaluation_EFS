<?php

ini_set('display_errors', 0);

$app = require __DIR__.'/../app/app.php';
require __DIR__.'/../app/config/dev.php';
require __DIR__.'/../app/routing.php';
$app->run();
