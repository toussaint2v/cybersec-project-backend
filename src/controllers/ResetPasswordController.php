<?php

namespace Src\controllers;


use Src\models\Email;
use Src\models\Profile;

class ResetPasswordController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public static function store($formData){


        echo json_encode((new Profile)->resetPassword($formData));
    }

    public static function sendEmail(string $email){

        $password_token = md5($email).rand(10,9999);

        $link = "<a href='http://localhost:8081/reset-password?email=".$email."&password_token=".$password_token."'>Click To Reset password</a>";

        $emailBody = 'Click On This Link to Reset Password ' . $link . '';
        $sendEmail = new Email($emailBody, $email);

        (new Profile)->setPasswordToken($password_token, $email);

        echo json_encode($sendEmail->send());

    }
}