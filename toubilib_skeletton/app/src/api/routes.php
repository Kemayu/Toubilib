<?php
declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use toubilib\api\actions\ListerPraticienAction;
use toubilib\api\actions\ListerCreneauDejaPraticien;
use toubilib\api\actions\ListerRDVbyId;


return function( \Slim\App $app):\Slim\App {

    $app->get('/praticiens', ListerPraticienAction::class);
    $app->get('/praticiens/{praticienId}/creneaux', ListerCreneauDejaPraticien::class);
    $app->get('/rdv/{id}', ListerRDVbyId::class);

    $app->get('/ping', function (Request $request, Response $response) {
        $response->getBody()->write('pong');
        return $response->withHeader('Content-Type', 'text/plain');
    });
    return $app;
};