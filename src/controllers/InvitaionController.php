<?php

namespace Src\controllers;

use Src\models\Invitation;

class InvitaionController
{
    public function store($from, $to){

        $invitation = new Invitation();
        if ($from !== $to){
            $res = $invitation->create($from, $to);
        }else{
            $res = [
                'status' => 400,
                'message' => "Impossible"
            ];
        }

        return $res;
    }
}