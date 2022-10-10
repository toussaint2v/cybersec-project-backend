<?php

namespace Src\controllers;

require_once "src/controllers/Controller.php";
require_once('src/models/Profile.php');
require_once('src/Validation.php');

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

        echo json_encode($profile->get($token));
    }


    public static function store(array $form)
    {
        $validation = new Validation();
        $status = 201;
        $mess = "L'utilisateur a été enregistrer";

        if ($form = $validation->validate($form)) {
            (new Profile())->create($form);
        } else {
            $mess = 'Veuillez renseigner tous les champs';
            $status = 422;
        }
        http_response_code($status);
        echo json_encode($mess);
    }

    public static function edit($token)
    {
        $profile = new Profile();
        echo json_encode($profile->get($token));
    }

    public static function update($token, $form)
    {
        $profile = new Profile();
        $validation = new Validation();
        if ($form = $validation->validate($form))
            $res = $profile->update($token, $form);
        else{
            http_response_code(422);
            $res = "Une erreur est survenu";
        }

        echo json_encode($res);;
    }

    public static function  getAll(){

        echo json_encode((new Profile())->getAll());
    }

    public static function searchProfiles($form){

        echo json_encode((new Profile())->search($form));;
    }


}