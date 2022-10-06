<?php

namespace Src\middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use Src\controllers\AuthController;

class AuthMiddlewareOld
{
    /**
     * Example middleware invokable class
     *
     * @param RequestHandler $handler PSR-15 request handler
     *
     * @return Response
     */
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $authController = new AuthController();
        $token = $request->getHeader('Authorization')[0];
        if ($token && $authController->checkToken($token)) {
            $response = $handler->handle($request);
            $existingContent = (string)$response->getBody();
            $response = new Response();
            $response->getBody()->write($existingContent);
        } else {
            $response = new Response();
            $response = $response->withStatus(401);
            $response->getBody()->write('veuillez vous connecter');

        }

        return $response;
    }
}