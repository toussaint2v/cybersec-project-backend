<?php

namespace Src\controllers;

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
        http_response_code(201);

        if ($form = $validation->validate($form)) {
            $mess = (new Profile())->create($form);
            EmailConfirmationController::sendEmail($form['email']);
        } else {
            $mess = 'Veuillez renseigner tous les champs';
            http_response_code(422);
        }
        echo json_encode($mess);
    }

    public static function edit($id)
    {
        $profile = new Profile();
        echo json_encode($profile->get($id));
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

    public static function searchProfiles($search, $idProfile){

        echo json_encode((new Profile())->search($search, $idProfile));;
    }

    public static function getFriends($idProfile){

        echo json_encode((new Profile())->getFriends($idProfile));
    }

}