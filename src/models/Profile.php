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


    public function get($token){
        $req = "SELECT username, address, name, first_name, age, birthDate FROM users WHERE token = ?";

        $profile = $this->connection->execute($req, array($token));

        return $profile;
    }

    public function getAll(){
        $req = "SELECT username, address, name, first_name, age, birthDate FROM users";

        $profiles = $this->connection->execute($req);

        return $profiles;
    }

    public function create($profile)
    {
        $res = false;
        try {
            $register = $this->connection->getPdo()->prepare('INSERT INTO users (email, password, name, first_name, username ) VALUE (?,?,?,?,?)');
            $register->execute(array($profile['email'], password_hash($profile['password'], PASSWORD_BCRYPT), $profile['name'],
                $profile['first_name'], $profile['username']));
        } catch (PDOException $e) {
            $status = 400;
            if ($e->getCode() == 23000)
                $mess = "L'email ou le nom d'utilisateur est déjà utilisé !";
            else
                $mess = $e->getMessage();

            $res = ['status' => $status, 'message' => $mess];
        }
        return $res;
    }

    public function update($token, $profile)
    {
        $status = 422;
        $mess = "Erreur";
        try {

            $sql = $this->connection->getPdo()->prepare("UPDATE users SET username = :username, name = :name, 
                 first_name = :first_name, birthDate = :birthDate,
                 age = :age, address = :address WHERE token = '$token'");

            $sql->execute($profile);
            $status = 200;
            $mess = 'Les informarions on été modifiées';

        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $status = 422;
                $mess = "L'email ou le nom d'utilisateur est déjà utilisé !!!";
            } else
                $mess = $e->getMessage();
        }
        $response = [
            'message' => $mess,
            'status' => $status
        ];

        return $response;
    }

    public function search($search){
        $req = "SELECT username, address, name, first_name, age, birthDate FROM users WHERE name LIKE '%{$search}%' OR
                 first_name LIKE '%{$search}%' OR  username LIKE '%{$search}%' ";

        $profiles = $this->connection->execute($req);

        return $profiles;
    }

}