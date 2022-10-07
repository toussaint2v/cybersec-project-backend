<?php
// configuration cors (header response)
cors();

// import des controllers
require_once 'src/controllers/AuthController.php';
require_once 'src/controllers/ProfileController.php';
require_once 'src/controllers/InvitaionController.php';
use Src\controllers\AuthController;
use Src\controllers\InvitaionController;
use Src\controllers\ProfileController;

// recuperation de la route demandée
$requestURL = $_SERVER['REQUEST_URI'];

// recuperation du header de la requête
$header = apache_request_headers();

// ROUTER
// auth user (route protégée par authentification, verification du token)
if (isset($header['Authorization']) && (new AuthController())->checkToken($header['Authorization'])) {
    // recuperation du type de requête
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            switch ($requestURL) {
                case '/api/profile/edit' :
                    $res = ProfileController::edit($header['Authorization']);
                    echo json_encode($res);
                    break;
                case '/api/profiles' :
                    $res = ProfileController::getAll();
                    echo json_encode($res);
                    break;
                case '/api/invitations' :
                    $res = (new InvitaionController())->getAll($header['Authorization']);
                    echo json_encode($res['data']);
                    break;
                default:
                    http_response_code(404);
                    break;
            }
            break;
        case 'POST':
            // recuperation des données envoyées par la requête
            $formData = json_decode(file_get_contents("php://input"), true);
            switch ($requestURL) {
                case '/api/login' :
                    $res = (new AuthController())->login($formData);
                    echo json_encode($res);
                    break;
                case '/api/logout':
                    $res = (new AuthController())->logout( $header['Authorization']);
                    echo json_encode($res);
                    break;
                case '/api/profile/update' :
                    $res = ProfileController::update($header['Authorization'], $formData);
                    echo json_encode($res);
                    break;
                case '/api/profiles/search' :
                    $res = ProfileController::searchProfiles($formData);
                    echo json_encode($res);
                    break;
                case '/api/invitation/send' :
                    $res = (new InvitaionController())->store($formData['from'], $formData['to']);
                    echo json_encode($res);
                    break;


                default:
                    http_response_code(404);
                    break;
            }
        case 'DELETE':

    }
}
// mauvais token
elseif (isset($header['Authorization']) && !(new AuthController())->checkToken($header['Authorization'])){
    http_response_code(401);
    echo "veuillez vous connecter";
}
// guest user (route réservée au utilisateur non authentifié
elseif ($_SERVER['REQUEST_METHOD'] === "POST") {
    // recuperation des données envoyées par la requête
    $formData = json_decode(file_get_contents("php://input"), true);
    switch ($requestURL) {
        case '/api/login' :
            $res = (new AuthController())->login($formData);
            http_response_code($res['status']);
            echo json_encode($res);
            break;
        case '/api/profile/store' :
            $res = ProfileController::store($formData);
            http_response_code($res['status']);
            echo json_encode($res['message']);
            break;
        default:
            http_response_code(404);
            break;
    }
}


// configuration cors
function cors()
{
    // Allow from any origin
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
        // you want to allow, and if so:
        header("Access-Control-Allow-Origin: http://localhost:8080");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }

    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            // may also be using PUT, PATCH, HEAD etc
            header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header('Access-Control-Allow-Headers: Content-Type, Accept, Authorization, X-Requested-With, Application, UserId');

        exit(0);
    }

}