<?php

namespace Src\models;

use PHPMailer\PHPMailer\PHPMailer;

class Email extends Model
{
    private PHPMailer $mail ;

    public function __construct(string $body, string $emailTo)
    {
        parent::__construct();
        $this->mail = new PHPMailer();

        $this->mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        $this->mail->IsSMTP();
        $this->mail->SMTPAuth = false;
        $this->mail->SMTPAutoTLS = false;
        // GMAIL username
        /*
        // sets GMAIL as the SMTP server
        $this->mail->Host = "localhost";
        // set the SMTP port for the GMAIL server
        $this->mail->Port = 1025;
        $this->mail->From = 'toussaint.carlotti@gmail.com';
        $this->mail->FromName = 'toussaint carlotti';
        $this->mail->AddAddress('toussaint.carlotti@gmail.com', 'toussaint carlotti');
        $this->mail->Subject = 'Reset Password';
        $this->mail->IsHTML(true);
        $this->mail->Body = $body;
        */

        $this->mail->Host       = 'smtp.gmail.com';
        $this->mail->SMTPAuth   = true;
        $this->mail->Username   = 'projectcybert90@gmail.com';
        $this->mail->Password   = 'Projectcybert90.';
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $this->mail->Port       = 465;

        $this->mail->From = 'projectcybert90@gmail.com';
        $this->mail->FromName = 'CyberSec Project';
        $this->mail->AddAddress($emailTo);
        $this->mail->Subject = 'Reset Password';
        $this->mail->IsHTML(true);
        $this->mail->Body = $body;

    }

    public function send(){
        if ($this->mail->Send()) {
            return "Check Your Email and Click on the link sent to your email";
        } else {
            return "Mail Error - >" . $this->mail->ErrorInfo;
        }
    }
}