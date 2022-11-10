<?php

namespace Src\models;

use Src\databases\Connection;

class Model
{
    protected $connection;
    public function __construct()
    {
        $this->connection = new Connection();
    }


}