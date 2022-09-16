<?php

namespace Src\models;

use PDOException;

class Invitation extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function create($invit){
        $status = 200;
        $mess = "L'invitation a bien été envoyée";
        try {
            $sql = $this->connection->getPdo()->prepare('INSERT INTO friends_invitations (`from`, `to`, status) VALUE (?,?,?)');
            $sql->execute(array($invit['from'], $invit['to'], $invit['accepted'], $invit['opened']));
        } catch (PDOException $e) {
            $status = 422;
            $mess = $e->getMessage();
        }
        return ["status" => $status, "message" => $mess ];
    }
}