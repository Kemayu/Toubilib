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

use toubilib\core\application\actions\SigninAction;
use toubilib\core\application\middlewares\AuthnMiddleware;
use toubilib\core\application\middlewares\AuthzMiddleware;

return function (App $app): App {

    $app->post('/auth/signin', SigninAction::class)->setName('auth.signin');

    $app->get('/praticiens', ListerPraticienAction::class);
    $app->get('/praticiens/{id}', PraticienDetailAction::class);
    $app->get('/patients', ListerPatientAction::class);
    $app->get('/patients/{id}', PatientDetailAction::class);

    $app->get('/praticiens/{praticienId}/creneaux', ListerCreneauDejaPraticien::class)
        ->setName('agenda')
        ->add(AuthzMiddleware::class)
        ->add(AuthnMiddleware::class);
    
    $app->get('/rdvs/{id}', ListerRDVbyId::class)
        ->setName('rdv.get')
        ->add(AuthzMiddleware::class)
        ->add(AuthnMiddleware::class);
    
    $app->post('/rdvs', CreateRdvAction::class)
        ->setName('rdv.create')
        ->add(ValidateInputRdv::class)
        ->add(AuthzMiddleware::class)
        ->add(AuthnMiddleware::class);
    
    $app->delete('/rdvs/{id}', AnnulerRdvAction::class)
        ->setName('rdv.delete')
        ->add(AuthzMiddleware::class)
        ->add(AuthnMiddleware::class);

    $app->patch('/rdvs/{id}', UpdateRdvStatusAction::class)
        ->setName('rdv.update')
        ->add(AuthzMiddleware::class)
        ->add(AuthnMiddleware::class);

   
    // Preflight CORS
    $app->options('/{routes:.+}', function (
        ServerRequestInterface $request,
        ResponseInterface $response
        ): ResponseInterface {
        return $response;
    });

    return $app;
};
