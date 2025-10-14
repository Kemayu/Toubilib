<?php
declare(strict_types=1);

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use toubilib\api\actions\AnnulerRdvAction;
use toubilib\api\actions\CreateRdvAction;
use toubilib\api\actions\ListerPatientAction;
use toubilib\api\actions\ListerPraticienAction;
use toubilib\api\actions\ListerCreneauDejaPraticien;
use toubilib\api\actions\ListerRDVbyId;
use toubilib\api\actions\PatientDetailAction;
use toubilib\api\actions\PraticienDetailAction;
use toubilib\api\actions\UpdateRdvStatusAction;
use toubilib\api\middlewares\ValidateInputRdv;

return function (App $app): App {

    // Routes de l'API
    $app->get('/praticiens', ListerPraticienAction::class);
    $app->get('/praticiens/{praticienId}/creneaux', ListerCreneauDejaPraticien::class);
    $app->get('/rdvs/{id}', ListerRDVbyId::class);
    $app->get('/praticiens/{id}', PraticienDetailAction::class);
    $app->post('/rdvs', CreateRdvAction::class)->add(ValidateInputRdv::class);
    $app->delete('/rdvs/{id}', AnnulerRdvAction::class);
    $app->get('/patients', ListerPatientAction::class);
    $app->get('/patients/{id}', PatientDetailAction::class);
    $app->patch('/rdvs/{id}', UpdateRdvStatusAction::class);


    $app->options('/{routes:.+}', function (
        ServerRequestInterface $request,
        ResponseInterface $response
        ): ResponseInterface {
        return $response;
    });

    return $app;
};
