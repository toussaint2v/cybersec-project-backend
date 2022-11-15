<?php

namespace Src\models;
use PDOException;

class Profile extends Model
{
    public function __construct()
    {
        parent::__construct();
    }


    public function get($id){
        $req = "SELECT id, username, address, name, first_name, birthDate FROM users WHERE id = ?";
        $profile = $this->connection->get($req, array($id));

        return $profile;
    }

    public function getAll(){
        $req = "SELECT id, username, address, name, first_name, birthDate FROM users";

        $profiles = $this->connection->getAll($req);

        return $profiles;
    }

    public function create($profile)
    {
        try {
            $register = $this->connection->getPdo()->prepare('INSERT INTO users (email, password, name, first_name, username, birthDate, address ) VALUE (?,?,?,?,?,?,?)');
            $register->execute(array($profile['email'], password_hash($profile['password'], PASSWORD_BCRYPT), $profile['name'],
                $profile['first_name'], $profile['username'], $profile['birthDate'], $profile['address']));
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
                  address = :address WHERE token = '$token' && id = :id");

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
        $req = "SELECT id, username, address, name, first_name, birthDate, `to`, `from`, `accepted` FROM users 
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

    public function setPasswordToken($reset_password_token, $email){
        return $this->connection->execute("UPDATE users SET reset_password_token = '$reset_password_token' WHERE email = '$email'");
    }

    public function updatePassword($email, $password){
        $sql = "UPDATE users SET password = ? WHERE email = ?";
        return $this->connection->execute($sql, array(password_hash($password, PASSWORD_BCRYPT), $email ));
    }
    public function resetPassword($formData){
        $req = "SELECT reset_password_token FROM users WHERE email = ?";

        if ($this->connection->get($req, array($formData['email']))['reset_password_token'] === $formData['password_token']){
            $res = $this->updatePassword($formData['email'], $formData['password']);
            if ($res === 'OK'){
                $this->connection->execute("UPDATE users SET reset_password_token = '' WHERE email = ?", array($formData['email']));
            }
        }else{
            $res = 'Erreur ! L\'email a probablement expiré';
            http_response_code(400);
        }
        return $res;
    }
}