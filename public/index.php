<?php

require_once __DIR__.'/../vendor/autoload.php';

use app\core\Application;
use app\controllers\UserController;
use app\migrations\Jahrein;
use app\routes\Route;

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$app = new Application($_ENV);

$route = new Route($app);

$app->run();

?>