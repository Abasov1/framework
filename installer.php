<?php 

$now = __DIR__;
$abasov1 = dirname(__DIR__);
$vendor = dirname($abasov1);
$dir = dirname($vendor);

$foldersToMove = [
    'controllers',
    'middlewares',
    'migrations',
    'models',
    'public',
    'requests',
    'routes',
    'views',
    '.env.example',
    '.gitignore',
    'composer.json',
    'composer.lock',
    'create_migration.php',
    'dropall.php',
    'migrate.php',
    'migrate_refresh.php',
    'Tutorial.php'

];

unlink($dir . '/' . 'composer.json');
unlink($dir . '/' . 'composer.lock');

foreach ($foldersToMove as $folder) {
    $sourceLocation = $now  . '/' . $folder;
    $destination = $dir . '/' . $folder;

    rename($sourceLocation, $destination);
}


unlink(__FILE__);

?>