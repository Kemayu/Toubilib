<?php
declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use toubilib\api\actions\ListerPraticienAction;


return function( \Slim\App $app):\Slim\App {

    $app->get('/praticiens', ListerPraticienAction::class);

    $app->get('/ping', function (Request $request, Response $response) {
        $response->getBody()->write('pong');
        return $response->withHeader('Content-Type', 'text/plain');
    });
    return $app;
};