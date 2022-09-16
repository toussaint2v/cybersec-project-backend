<?php
namespace Src;
class Validation
{
    public function validate(array $data){

        $isEmpty = false;

        foreach ($data as $key => $e){
            if ($e == '' | !$e | $e == null){
                $isEmpty = true;
            }
            $e = trim((String) $e);
            $e = stripslashes($e);
            $data[$key] = htmlspecialchars($e);
        }
        $res = $data;

        if ($isEmpty)
            $res = false;

        return $res;
    }
}