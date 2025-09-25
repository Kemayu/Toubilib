<?php
declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use toubilib\api\actions\CreateRdvAction;
use toubilib\api\actions\ListerPraticienAction;
use toubilib\api\actions\ListerCreneauDejaPraticien;
use toubilib\api\actions\ListerRDVbyId;
use toubilib\api\actions\PraticienDetailAction;
use toubilib\api\middlewares\ValidateInputRdv;

return function( \Slim\App $app):\Slim\App {

    $app->get('/praticiens', ListerPraticienAction::class);
    $app->get('/praticiens/{praticienId}/creneaux', ListerCreneauDejaPraticien::class);
    $app->get('/rdv/{id}', ListerRDVbyId::class);
     $app->get('/praticiens/{id}', PraticienDetailAction::class);
    $app->post('/rdv', CreateRdvAction::class)->add(ValidateInputRdv::class);


    return $app;
};
