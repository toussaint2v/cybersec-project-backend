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

        $res = $this->connection->execute('INSERT INTO friends_invitations (`from`, `to`,`accepted`,`opened`) VALUE (?,?,?,?)',
            array($from, $to, 0, 0 ));
        if ($res === "OK"){
            $res = "L'invitation a bien été envoyée";
            http_response_code(201);
        }
        return $res;
    }

    public function getAll($token){
        $sql = 'SELECT id, username, address, name, first_name, age, birthDate,`to`,`from` FROM users 
            JOIN friends_invitations ON users.id = friends_invitations.`to` WHERE token = ? AND accepted = false';
        $res =  $this->connection->getAll($sql, array($token));

        return $res;
    }

    public function delete($from, $to){
        $sql = 'DELETE FROM cyber_sec.friends_invitations WHERE `from` = ? AND `to` = ?';
        $res =  $this->connection->execute($sql, array($from, $to));
        if ($res === 'OK'){
            http_response_code(204);
        }
        return $res;
    }

    public function accept($from, $to){
        $sql = 'UPDATE friends_invitations SET `accepted` =  ? WHERE `from` = ? AND `to` = ? ';
        $res =  $this->connection->execute($sql, array(1, $from, $to));
        return $res;
    }

    public function count($profileId){
        $sql = 'SELECT count(*) as notif FROM friends_invitations WHERE `to` = ? AND opened = 0 ';
        $res =  $this->connection->get($sql, array($profileId));
        return $res;
    }

    public function openAll($profileId){
        $sql = 'UPDATE friends_invitations SET `opened` =  ? WHERE `to` = ? ';
        $res =  $this->connection->execute($sql, array(1, $profileId));
        if ($res === 'OK'){
            http_response_code(204);
        }
    }
}

