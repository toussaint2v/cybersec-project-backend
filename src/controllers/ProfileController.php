<?php

namespace Src\controllers;
use Src\models\Invitation;
use Src\models\Profile;
use Src\Validation;

require_once('src/models/Profile.php');
require_once('src/Validation.php');


class ProfileController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function get($userId, $token){

        $profile = new Profile();
        return $profile->get($userId, $token);
    }


    public function store(array $form)
    {
        $validation = new Validation();
        $status = 200;
        $mess = "L'utilisateur a été enregistrer";

        if ($form = $validation->validate($form)) {
            $profile = new Profile();
            if ($error = $profile->create($form)) {
                $status = $error['status'];
                $mess = $error['message'];
            }
        } else {
            $mess = 'Veuillez renseigner tous les champs';
            $status = 422;
        }
        return ['status' => $status, 'message' => $mess];
    }

    public function edit($userId, $token)
    {
        $profile = new Profile();
        return $profile->get($userId, $token);
    }

    public function update($token, $form)
    {
        $profile = new Profile();
        $validation = new Validation();
        if ($form = $validation->validate($form))
            $res = $profile->update($token, $form);
        else{
            $res = [
                'message' => "Une erreur est survenu",
                'status' => 422
            ];
        }
        return $res;
    }

    public function sendInvitation($from, $to){
        $status = 200;
        $invitation = new Invitation();
        $res = $invitation->create([$from, $to, false, false]);
        if ($res){
            $res = "Invitation envoyer";
        }else {
            $status = 422;
            $res = "Erreur";
        }
        return [$res, $status];
    }

    public function friends(){

    }
}