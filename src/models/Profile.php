<?php

namespace Src\models;
use PDOException;
require_once('src/models/Model.php');


class Profile extends Model
{

    public function __construct()
    {
        parent::__construct();
    }


    public function get($id){
        $req = "SELECT id, username, address, name, first_name, age, birthDate FROM users WHERE id = ?";
        $profile = $this->connection->get($req, array($id));

        return $profile;
    }

    public function getAll(){
        $req = "SELECT id, username, address, name, first_name, age, birthDate FROM users";

        $profiles = $this->connection->getAll($req);

        return $profiles;
    }

    public function create($profile)
    {
        try {
            $register = $this->connection->getPdo()->prepare('INSERT INTO users (email, password, name, first_name, username ) VALUE (?,?,?,?,?)');
            $register->execute(array($profile['email'], password_hash($profile['password'], PASSWORD_BCRYPT), $profile['name'],
                $profile['first_name'], $profile['username']));
            $mess = "L'utilisateur a été enregistrer";
        } catch (PDOException $e) {
            $status = 400;
            if ($e->getCode() == 23000)
                $mess = "L'email ou le nom d'utilisateur est déjà utilisé !";
            else
                $mess = $e->getMessage();


            http_response_code($status);
        }
        return $mess;
    }

    public function update($token, $profile)
    {
        $status = 422;
        try {

            $sql = $this->connection->getPdo()->prepare("UPDATE users SET username = :username, name = :name, 
                 first_name = :first_name, birthDate = :birthDate,
                 age = :age, address = :address WHERE token = '$token' && id = :id");

            $sql->execute($profile);
            $status = 200;
            $mess = 'Les informarions on été modifiées';

        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $mess = "L'email ou le nom d'utilisateur est déjà utilisé !!!";
            } else
                $mess = $e->getMessage();
        }

        http_response_code($status);
        return $mess;
    }

    public function search($search, $idProfile){
        $req = "SELECT id, username, address, name, first_name, age, birthDate, `to`, `from`, `accepted` FROM users 
               LEFT JOIN friends_invitations ON users.id = friends_invitations.`to` WHERE (name LIKE '%{$search}%' OR 
                first_name LIKE '%{$search}%' OR username LIKE '%{$search}%') AND {$idProfile} <> id ";

        $profiles = $this->connection->getAll($req);

        return $profiles;
    }

    public function getFriends($idProfile){
        $req = "SELECT id, name, first_name, username, `to`, `from` FROM users 
               LEFT JOIN friends_invitations ON users.id = friends_invitations.`to` OR
                                                users.id = friends_invitations.`from` WHERE accepted = 1 AND {$idProfile} = id ";

        $profiles = $this->connection->getAll($req);
        return $profiles;
    }
}