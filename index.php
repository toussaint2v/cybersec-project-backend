<?php
require 'vendor/autoload.php';



// import des controllers
use Src\controllers\AuthController;
use Src\controllers\InvitaionController;
use Src\controllers\ProfileController;
use Src\controllers\ResetPasswordController;
use Src\config\DotEnv;
(new DotEnv(__DIR__ . '/.env'))->load();

// configuration cors (header response)
cors();

// recuperation de la route demandée
$requestURL = $_SERVER['REQUEST_URI'];

// recuperation du header de la requête
$header = apache_request_headers();
$url_components = parse_url($requestURL);
$requestURL = $url_components['path'];
// ROUTER
// auth user (route protégée par authentification, verification du token)
if (isset($header['Authorization']) && (new AuthController())->checkToken($header['Authorization'])) {
    $token = $header['Authorization'];
    // recuperation du type de requête
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            switch ($requestURL) {
                case '/api/profile/edit' :
                    ProfileController::edit($_GET['id']);
                    break;
                case '/api/profiles' :
                    ProfileController::getAll();
                    break;
                case '/api/invitations' :
                    InvitaionController::getAll($token);
                    break;
                case '/api/profiles/search' :
                    ProfileController::searchProfiles($_GET['search'], $_GET['idProfile']);
                    break;
                case '/api/friends' :
                    ProfileController::getFriends($_GET['idProfile']);
                    break;
                case '/api/invitation/count':
                    InvitaionController::count($_GET['profileId']);
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
                case '/api/logout':
                    (new AuthController())->logout($token);
                    break;
                case '/api/profile/update' :
                    ProfileController::update($token, $formData);
                    break;
                case '/api/invitation/send' :
                    InvitaionController::store($formData['from'], $formData['to']);
                    break;
                case '/api/invitation/accept' :
                    InvitaionController::accept($formData['from'], $formData['to']);
                    break;
                case '/api/invitations/open' :
                    InvitaionController::openAll($formData['profileId']);
                    break;


                default:
                    http_response_code(404);
                    break;
            }
            break;
        case 'DELETE':
            $url_components = parse_url($requestURL);
            $requestURL = $url_components['path'];
            switch ($requestURL) {
                case '/api/invitation/destroy':
                    InvitaionController::destroy($_GET['from'], $_GET['to']);
                    break;
            }


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
        case '/api/login':
            (new AuthController())->login($formData);
            break;
        case '/api/profile/store':
            ProfileController::store($formData);
            break;
        default:
            http_response_code(404);
            break;
        case '/api/reset-password/sendEmail' :
            ResetPasswordController::sendEmail($formData['email']);
            break;
        case '/api/reset-password/store' :
            ResetPasswordController::store($formData);
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
        header('Access-Control-Allow-Origin: '.getenv('CORS_ORIGIN'));
        header('Access-Control-Allow-Credentials: true');
    }

    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            // may also be using PUT, PATCH, HEAD etc
            header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header('Access-Control-Allow-Headers: Content-Type, Accept, Authorization, X-Requested-With, Application');

        exit(0);
    }

}