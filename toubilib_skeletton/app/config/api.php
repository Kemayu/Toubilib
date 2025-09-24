<?php

use Psr\Container\ContainerInterface;
use toubilib\api\actions\ListerPraticienAction;
use toubilib\core\application\ports\spi\repositoryInterfaces\ServicePraticienInterface;
use toubilib\api\actions\ListerCreneauDejaPraticien;
use toubilib\core\application\ports\spi\repositoryInterfaces\RdvRepositoryInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\ServiceRendezVousInterface;
use toubilib\api\actions\ListerRDVbyId;

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
];