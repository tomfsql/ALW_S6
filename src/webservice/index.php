<?php
session_start();
use App\Application;

ini_set('display_errors', 'On');

require_once('Framework/autoloader.php');

// start the application
$app = new Application();
$app->run();