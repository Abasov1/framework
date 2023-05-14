<?php 

$now = __DIR__;
$dir = dirname($now);

$foldersToMove = [
    'controllers',
    'core',
    'middlewares',
    'migrations',
    'models',
    'public',
    'requests',
    'routes',
    'vendor',
    'views',
    '.env',
    '.env.example',
    '.gitignore',
    'composer.json',
    'composer.lock',
    'create_migration.php',
    'dropall.php',
    'delete.php',
    'migrate.php',
    'migrate_refresh.php',
    'Tutorial.php'

];


foreach ($foldersToMove as $folder) {
    $sourceLocation = $now  . '/' . $folder;
    $destination = $dir . '/' . $folder;

    rename($sourceLocation, $destination);
}
unlink($dir . '/' . 'README.md');
unlink(__FILE__);

?>