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

    public static function store($formData)
    {


        echo json_encode((new Profile)->resetPassword($formData));
    }

    public static function sendEmail(string $email)
    {
        $password_token = md5($email) . rand(10, 9999);

        $body = '
            <!doctype html>
            <html lang="fr">
            
            <head>
                <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
                <title>Réinitialisation de mot de passe</title>
                <meta name="description" content="Reset Password Email Template.">
                <style type="text/css">
                    a:hover {text-decoration: underline !important;}
                </style>
            </head>
            
            <body marginheight="0" topmargin="0" marginwidth="0" style="margin: 0px; background-color: #f2f3f8;" leftmargin="0">
            <!--100% body table-->
            <table cellspacing="0" border="0" cellpadding="0" width="100%" bgcolor="#f2f3f8">
                <tr>
                    <td>
                        <table style="background-color: #f2f3f8; max-width:670px;  margin:0 auto;" width="100%" border="0"
                               align="center" cellpadding="0" cellspacing="0">
                            <tr>
                                <td style="height:80px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td style="text-align:center;">
                                    <a style="text-decoration:none !important;color: #455056" href="' . getenv("APP_URL") . '" title="logo" target="_blank">
                                        <h1>' . getenv("APP_NAME") . '</h1>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td style="height:20px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td>
                                    <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0"
                                           style="max-width:670px;background:#fff; border-radius:3px; text-align:center;-webkit-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);-moz-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);box-shadow:0 6px 18px 0 rgba(0,0,0,.06);">
                                        <tr>
                                            <td style="height:40px;">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td style="padding:0 35px;">
                                                <h1 style="color:#1e1e2d; font-weight:500; margin:0;font-size:32px;">Vous avez demandé une réinitialisation de mot de passe</h1>
                                                <span
                                                        style="display:inline-block; vertical-align:middle; margin:29px 0 26px; border-bottom:1px solid #cecece; width:100px;"></span>
                                                <p style="color:#455056; font-size:15px;line-height:24px; margin:0;">
                                                    Veuillez clicker sur le bouton afin de réinitialiser votre mot de passe.
                                                </p>
                                                <a href="' . getenv('APP_URL') . '/reset-password?email=' . $email . '&password_token=' . $password_token . '"
                                                   style="background:#b820e2;text-decoration:none !important; font-weight:500; margin-top:35px; color:#fff;text-transform:uppercase; font-size:14px;padding:10px 24px;display:inline-block;border-radius:50px;">
                                                   Réinitialiser le mot de passe
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="height:40px;">&nbsp;</td>
                                        </tr>
                                    </table>
                                </td>
                            <tr>
                                <td style="height:20px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td style="text-align:center;">
                                    <p style="font-size:14px; color:rgba(69, 80, 86, 0.7411764705882353); line-height:18px; margin:0 0 0;">&copy; <strong>'.getenv('APP_URL').'</strong></p>
                                </td>
                            </tr>
                            <tr>
                                <td style="height:80px;">&nbsp;</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <!--/100% body table-->
            </body>
            
            </html>';

        //$link = "<a href='".getenv('APP_URL')."/reset-password?email=".$email."&password_token=".$password_token."'>Click To Reset password</a>";

        (new Profile)->setPasswordToken($password_token, $email);

        $sendEmail = new Email($body, $email);

        echo json_encode($sendEmail->send());


    }

}