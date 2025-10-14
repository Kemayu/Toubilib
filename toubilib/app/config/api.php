<?php

use Psr\Container\ContainerInterface;
use toubilib\api\actions\ListerPraticienAction;
use toubilib\core\application\ports\spi\repositoryInterfaces\ServicePraticienInterface;
use toubilib\api\actions\ListerCreneauDejaPraticien;
use toubilib\core\application\ports\spi\repositoryInterfaces\ServiceRendezVousInterface;
use toubilib\api\actions\ListerRDVbyId;
use toubilib\api\actions\CreateRdvAction;
use toubilib\api\actions\AnnulerRdvAction;
use toubilib\api\middlewares\ValidateInputRdv;
use toubilib\api\middlewares\Cors;

return [
// application
    ListerPraticienAction::class=> function (ContainerInterface $c) {
        return new ListerPraticienAction($c->get(ServicePraticienInterface::class));
    },
    ListerCreneauDejaPraticien::class => function (ContainerInterface $c) {
        return new ListerCreneauDejaPraticien($c->get(ServiceRendezVousInterface::class));
    },
    ListerRDVbyId::class => function (ContainerInterface $c) {
        return new ListerRDVbyId($c->get(ServiceRendezVousInterface::class));
    },
    CreateRdvAction::class => function (ContainerInterface $c) {
        return new CreateRdvAction($c->get(ServiceRendezVousInterface::class));
    },
    AnnulerRdvAction::class => function (ContainerInterface $c) {
        return new AnnulerRdvAction($c->get(ServiceRendezVousInterface::class));
    },
    
    // middlewares
    ValidateInputRdv::class => function (ContainerInterface $c) {
        return new ValidateInputRdv();
    },
    
    Cors::class => function (ContainerInterface $c) {
        
        return new Cors(
            allowedOrigins: ['*'],  // ['http://localhost:6080']
            allowedMethods: ['GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'OPTIONS'],
            allowedHeaders: ['Content-Type', 'Authorization', 'X-Requested-With'],
            maxAge: 3600,  //1h
            allowCredentials: true, 
            strictMode: true 
        );
    },
];