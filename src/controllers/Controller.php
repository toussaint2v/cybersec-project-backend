<?php

namespace Src\controllers;


use Src\databases\Connection;

class Controller
{
    protected Connection $connection;
    public function __construct()
    {
        $this->connection = new Connection();
    }
}