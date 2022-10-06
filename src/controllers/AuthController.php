<?php


namespace Src\controllers;
include('src/utils/function.php');

require_once 'src/controllers/Controller.php';
require_once 'src/Validation.php';

use Psr\Http\Message\ResponseInterface as Response;
use Src\Validation;


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
                    $profile = $this->connection->get($req, array($user['id'], $token));

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

    public function logout($token)
    {
        $req = $this->connection->execute("UPDATE users SET token = null WHERE token = ?", array($token));
        $response = [
            'message' => $req['message'],
            'status' => $req['status']
        ];
        http_response_code(200);

        return $response;
    }

    function checkToken($token){
        $rt = false;
        $sql = $this->connection->getPdo()->prepare( "SELECT id, username, email, address, name, first_name, age FROM users WHERE token = ?");
        $sql->execute(array($token));
        $verif_ok = $sql->rowCount();
        if ($verif_ok === 1)
            $rt = true;
        return $rt;
    }
}