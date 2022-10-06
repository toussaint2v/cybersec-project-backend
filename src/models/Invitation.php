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

    public function create($invit){
        $status = 201;
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