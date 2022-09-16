<?php

namespace Src\controllers;
include('src/utils/function.php');

use Psr\Http\Message\ResponseInterface as Response;
use Src\Validation;
use function test_input;


class AuthController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function login(array $userInfo)
    {

        $validation = new Validation();
        if ($userInfo = $validation->validate($userInfo)) {
            $email = $userInfo['email'];
            $mdp = $userInfo['password'];
            $verif = $this->connection->getPdo()->prepare("SELECT * FROM users WHERE email = ? ");
            $verif->execute(array($email));
            $verif_ok = $verif->rowCount();


            if ($verif_ok === 1) {
                $user = $verif->fetch();
                $response = [
                    'message' => "Adresse e-mail ou mot de passe incorrect",
                    'status' => 422
                ];
                if (password_verify($mdp, $user['password'])) {

                    $token = openssl_random_pseudo_bytes(64);
                    $token = bin2hex($token);
                    $this->connection->execute("UPDATE users SET token = ? WHERE email = ?", array($token, $email));

                    $req = "SELECT id, username, address, name, first_name, age, birthDate FROM users WHERE id = ? AND token = ?";
                    $profile = $this->connection->execute($req, array($user['id'], $token));

                    $response = [
                        'message' => "Connexion réussie",
                        'status' => 200,
                        'profile' => $profile,
                        'token' => $token
                    ];
                }
            }else{
                $response = [
                    'message' => "Adresse e-mail ou mot de passe incorrect",
                    'status' => 422
                ];
            }
        }else{
            $response = [
                'message' => "Veuillez renseigner tous les champs",
                'status' => 422
            ];
        }

        return $response;
    }

    public function logout($token, Response $response)
    {
        $this->connection->execute("UPDATE users SET token = null WHERE token = ?", array($token));
        $response->getBody()->write('deconnexion réussie');
        return $response;
    }

    function checkToken($userId, $token){
        $rt = false;
        $sql = $this->connection->getPdo()->prepare( "SELECT id, username, email, address, name, first_name, age FROM users WHERE id = ? AND token = ?");
        $sql->execute(array($userId, $token));
        $verif_ok = $sql->rowCount();
        if ($verif_ok === 1)
            $rt = true;
        return $rt;
    }
}