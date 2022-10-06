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
        $req = "SELECT id, username, address, name, first_name, age, birthDate FROM users WHERE token = ?";

        $profile = $this->connection->get($req, array($token));

        return $profile;
    }

    public function getAll(){
        $req = "SELECT id, username, address, name, first_name, age, birthDate FROM users";

        $profiles = $this->connection->getAll($req);

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
                 age = :age, address = :address WHERE token = '$token' && id = :id");

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

    public function search($form){
        $req = "SELECT id, username, address, name, first_name, age, birthDate, `to`, `accepted` FROM users 
               LEFT JOIN friends_invitations ON users.id = friends_invitations.`to` WHERE (name LIKE '%{$form['search']}%' OR 
                first_name LIKE '%{$form['search']}%' OR username LIKE '%{$form['search']}%') AND {$form['idProfile']} <> id ";

        $profiles = $this->connection->getAll($req);

        return $profiles;
    }

}