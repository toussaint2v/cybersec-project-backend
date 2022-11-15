<?php
require 'vendor/autoload.php';

use Src\databases\Connection;
use Src\config\DotEnv;

(new DotEnv('.env'))->load();

$host = getenv('DB_HOST');
$dbname = getenv('DB_DATABASE');
$username = getenv('DB_USERNAME');
$password = getenv('DB_PASSWORD');

$myPDO = new PDO("mysql:host=$host", $username, $password);


$myPDO->exec('DROP DATABASE IF EXISTS `' . $dbname . '`');
$myPDO->exec('CREATE DATABASE `' . $dbname . '`');

$connection = new Connection();
$migrations = scandir('src/databases/migrations');

foreach ($migrations as $migration) {
    if (str_contains($migration, 'table')){
        $migration = file_get_contents('src/databases/migrations/'.$migration);
        $connection->getPdo()->exec($migration);
    }
}