<?php

require_once __DIR__.'/../vendor/autoload.php';

use app\core\Application;
use app\controllers\UserController;
use app\migrations\Jahrein;
use app\routes\Route;

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$cfg = [
	'dsn' => $_ENV['DB'],
	'user' => $_ENV['DB_USER'],
	'password' => $_ENV['DB_PASSWORD'],
	'name' => $_ENV['DB_NAME'],
];

$app = new Application($cfg,$_ENV);

$route = new Route($app);

$app->run();

?>