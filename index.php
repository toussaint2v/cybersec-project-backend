<?php

require_once __DIR__.'/vendor/autoload.php';
require_once('src/controllers/ProfileController.php');
require_once('src/controllers/AuthController.php');
require_once('src/middleware/AuthMiddleware.php');

use Src\controllers\AuthController;
use Src\controllers\ProfileController;
use Src\middleware\AuthMiddleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;

header('Access-Control-Allow-Origin: http://localhost:8080');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type, Accept, Authorization, X-Requested-With, Application, UserId');



$app = AppFactory::create();

//Router (slim micro framework)

//api group prefix
$app->group('/api', function (RouteCollectorProxy $api){
    //create profile
    $api->post('/profile/store', function (Request $request, Response $response){
        $data = json_decode($request->getBody(), true);
        $profileController = new ProfileController();
        $res = $profileController->store($data);
        $response->getBody()->write($res['message']);
        return $response->withStatus($res['status']);
    });


    //login
    $api->post('/login', function (Request $request, Response $response, $args){
        $user = json_decode($request->getBody(), true);
        $authController = new AuthController();
        $res = $authController->login($user);
        $response->getBody()->write(json_encode($res));
        return $response->withStatus($res['status']);
    });

    //authentification required route (middleware)
    $api->group('', function (RouteCollectorProxy $auth) {
        //logout
        $auth->post('/logout', function (Request $request, Response $response, $args) {
            $authController = new AuthController();
            $token = $request->getHeader('Authorization');
            $res = $authController->logout($token[0], $response);
            return $res;
        });

        //Profile group prefix
        $auth->group('/profile', function (RouteCollectorProxy $profile){
            //edit profile
            $profile->get('/edit', function (Request $request, Response $response) {
                $profileController = new ProfileController();
                $token = $request->getHeader('Authorization')[0];
                $userId = $request->getHeader('UserId')[0];
                $res = $profileController->edit($userId, $token);
                $response->getBody()->write(json_encode($res['data']));

                return $response->withStatus($res['status']);
            });

            //update profile
            $profile->post('/update', function (Request $request, Response $response) {
                $data = json_decode($request->getBody(), true);
                $profileController = new ProfileController();
                $res = $profileController->update($request->getHeader('Authorization'), $data);
                $response->getBody()->write(json_encode($res['message']));

                return $response->withStatus($res['status']);
            });
        });

        //friend

        $auth->post('/invitation/send', function (Request $request, Response $response, $args) {
            $data = json_decode($request->getBody(), true);
            $profileController = new ProfileController();
            $res = $profileController->sendInvitation($data['from'], $data['to']);
            $response->getBody()->write(json_encode($res[0]));
            $response = $response->withStatus($res[1]);
            return $response;
        });

    })->add(new AuthMiddleware());
});

$app->run();

