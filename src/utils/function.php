<?php

use Src\databases\Connection;

$connection = new Connection();
global $pdo;
$pdo = $connection->getPdo();

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}



?>