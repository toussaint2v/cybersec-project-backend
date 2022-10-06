<?php

namespace Src\controllers;

use Src\models\Invitation;

class InvitaionController
{
    public function store($from, $to){
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
}