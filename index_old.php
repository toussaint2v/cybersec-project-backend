<?php


use Src\controllers\AuthController;



header('Access-Control-Allow-Origin: http://localhost:8080');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type, Accept, Authorization, X-Requested-With, Application, UserId');

$requestURL = $_SERVER['REQUEST_URI'];

switch($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $request = &$_GET;
        switch ($requestURL) {
            case '/' :
               echo "hello";
                break;
            default:
                http_response_code(404);
                break;
        }
        break;
    case 'POST':
        $request = &$_POST;
        switch ($requestURL) {
            case '/api/login' :
                $user = json_decode($request);
                $authController = new AuthController();
                $res = $authController->login($user);
                echo json_encode('eefrf');
                break;
            default:
                http_response_code(404);
                break;
        }
        break;
    default:
}