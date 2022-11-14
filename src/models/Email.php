<?php

namespace Src\models;

use PHPMailer\PHPMailer\PHPMailer;

class Email extends Model
{
    private PHPMailer $mail ;

    public function __construct(string $body, string $mailTo)
    {
        parent::__construct();
        $this->mail = new PHPMailer();

        $this->mail->IsSMTP();
        $this->mail->Host = getenv('MAIL_HOST');

        // enable SMTP authentication
        if (getenv('MAIL_USERNAME')) {
            $this->mail->SMTPAuth = true;
            // GMAIL username
            $this->mail->Username = getenv('MAIL_USERNAME');
            // GMAIL password
            $this->mail->Password = getenv('MAIL_PASSWORD');
        }
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $this->mail->Port = getenv('MAIL_PORT');

        $this->mail->From = getenv('MAIL_FROM_ADDRESS');
        $this->mail->FromName = getenv('APP_NAME');
        $this->mail->AddAddress($mailTo);
        $this->mail->Subject = 'Reset Password';
        $this->mail->IsHTML();
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
