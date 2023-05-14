<?php 

$now = __DIR__;
$dir = dirname($now);

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