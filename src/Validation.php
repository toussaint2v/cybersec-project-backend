<?php
namespace Src;
class Validation
{
    public function validate(array $data){

        $isEmpty = false;

        foreach ($data as $key => $e){
            if ($e == '' | !$e | $e == null){
                $isEmpty = true;
            }else{
                $e = trim((String) $e);
                $e = stripslashes($e);
                $data[$key] = htmlspecialchars($e);
            }
        }
        $res = $data;

        if ($isEmpty){
            http_response_code(422);
            echo "Veuillez remplir tous les champs";
            exit(0);
        }


        return $res;
    }
}