<?php

require_once __DIR__.'/vendor/autoload.php';

use app\core\Application;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$cfg = [
	'dsn' => $_ENV['DB'],
	'user' => $_ENV['DB_USER'],
	'password' => $_ENV['DB_PASSWORD'],
	'name' => $_ENV['DB_NAME'],
];
$app = new Application($cfg,$_ENV);

$app->db->dropAll();


?>