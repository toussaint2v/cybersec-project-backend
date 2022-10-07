<?php

namespace Src\controllers;

require_once "src/controllers/Controller.php";

require_once('src/models/Invitation.php');
require_once('src/models/Profile.php');
require_once('src/Validation.php');

use Src\models\Invitation;
use Src\models\Profile;
use Src\Validation;




class ProfileController extends Controller
{


    public function __construct()
    {
        parent::__construct();
    }

    public static function get($token){

        $profile = new Profile();
        return $profile->get($token);
    }


    public static function store(array $form)
    {
        $validation = new Validation();
        $status = 201;
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

    public static function edit($token)
    {

        $profile = new Profile();

        return $profile->get($token);
    }

    public static function update($token, $form)
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

    public static function  getAll(){
        return (new Profile())->getAll();
    }

    public static function searchProfiles($form){
        return (new Profile())->search($form);
    }


}