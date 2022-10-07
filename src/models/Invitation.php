<?php

namespace Src\models;
require_once('src/models/Model.php');

use PDOException;


class Invitation extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function create($from, $to){
        $status = 201;
        $mess = "L'invitation a bien été envoyée";
        try {
            $sql = $this->connection->getPdo()->prepare('INSERT INTO friends_invitations (`from`, `to`,`accepted`,`opened`) VALUE (?,?,?,?)');
            $sql->execute(array($from, $to, 0, 0 ));
        } catch (PDOException $e) {
            $status = 422;
            $mess = $e->getMessage();
        }
        return ["status" => $status, "message" => $mess ];
    }

    public function getAll($token){
        $sql = 'SELECT id, username, address, name, first_name, age, birthDate,`to`,`from` FROM users 
            JOIN friends_invitations ON users.id = friends_invitations.`to` WHERE token = ? AND accepted = false';
        $res =  $this->connection->getAll($sql, array($token));

        return $res;
    }
}

