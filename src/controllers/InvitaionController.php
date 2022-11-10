<?php

namespace Src\controllers;

use Src\models\Invitation;

class InvitaionController
{
    public static function store($from, $to){

        $invitation = new Invitation();
        if ($from !== $to){
            $res = $invitation->create($from, $to);
        }else{
            $res = [
                'status' => 400,
                'message' => "Impossible"
            ];
        }
        echo json_encode($res);
    }

    public static function getAll($token){

        $invitation = new Invitation();
        $res = $invitation->getAll($token);

        echo json_encode($res);
    }

    public static function destroy($from, $to){

        $invitation = new Invitation();
        $res = $invitation->delete($from, $to);

        echo json_encode($res);
    }

    public static function accept($from, $to){

        $invitation = new Invitation();
        $res = $invitation->accept($from, $to);

        echo json_encode($res);
    }

    public static function count($profileId){
        $invitation = new Invitation();
        $res = $invitation->count($profileId);

        echo json_encode($res);
    }

    public static function openAll($profileId){
        $invitation = new Invitation();
        $invitation->openAll($profileId);

    }
}