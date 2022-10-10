<?php

namespace Src\models;


use PDOException;
require_once "src/databases/Connection.php";
use Src\databases\Connection;

class Model
{
    protected $connection;
    public function __construct()
    {
        $this->connection = new Connection();
    }


}