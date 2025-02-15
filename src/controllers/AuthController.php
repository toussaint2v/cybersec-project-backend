<?php


namespace Src\controllers;

use Src\Validation;


class AuthController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function login(array $userInfo)
    {
        $response = 'Erreur';
        $validation = new Validation();
        if ($userInfo = $validation->validate($userInfo)) {
            $email = $userInfo['email'];
            $mdp = $userInfo['password'];
            $verif = $this->connection->getPdo()->prepare("SELECT * FROM users WHERE email = ?");
            $verif->execute(array($email));
            $verif_ok = 0;
            if ($verif->rowCount() > 0) {
                $verif = $this->connection->getPdo()->prepare("SELECT * FROM users WHERE email = ? AND isValidated = true");
                $verif->execute(array($email));
                if ($verif->rowCount() > 0 ){
                    $verif_ok = 1;
                }else{
                    $response = "Veuillez confirmer votre adresse email";
                    EmailConfirmationController::sendEmail($email);
                    http_response_code(422);
                }
            }else{
                $response = "Adresse e-mail ou mot de passe incorrect";
                http_response_code(422);
            }
            if ($verif_ok === 1) {

                $user = $verif->fetch();

                $response =  $user['password'];
                if (password_verify($mdp, $user['password'])) {

                    $token = openssl_random_pseudo_bytes(64);
                    $token = bin2hex($token);
                    $this->connection->execute("UPDATE users SET token = ? WHERE email = ?", array($token, $email));
                    $req = "SELECT id, username, address, name, first_name, birthDate FROM users WHERE id = ? AND token = ? AND isValidated = true";
                    $profile = $this->connection->get($req, array($user['id'], $token));
                    http_response_code(200);
                    $response = [
                        'message' => "Connexion réussie",
                        'profile' => $profile,
                        'token' => $token
                    ];
                }
            }
        }else{
            $response = "Veuillez renseigner tous les champs";
            http_response_code(422);
        }
        echo json_encode($response);
    }

    public function logout($token)
    {
        $message = "Erreur";
        $res = $this->connection->execute("UPDATE users SET token = null WHERE token = ?", array($token));

        if($res === "OK"){
            $message = "Déconnexion réussie";
        }
        echo json_encode($message);
    }

    function checkToken($token){
        $rt = false;
        $sql = $this->connection->getPdo()->prepare( "SELECT id, username, email, address, name, first_name FROM users WHERE token = ? AND isValidated = true");
        $sql->execute(array($token));
        $verif_ok = $sql->rowCount();
        if ($verif_ok === 1)
            $rt = true;
        return $rt;
    }
}
