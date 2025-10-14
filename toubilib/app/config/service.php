<?php

use Psr\Container\ContainerInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\ServicePatientInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\ServicePraticienInterface;
use toubilib\core\application\usecases\ServicePraticien;
use toubilib\infra\repositories\PDOPraticienRepository;
use toubilib\core\application\ports\spi\repositoryInterfaces\RdvRepositoryInterface;
use toubilib\infra\repositories\PgRdvRepository;
use toubilib\core\application\ports\spi\repositoryInterfaces\ServiceRendezVousInterface;
use toubilib\core\application\usecases\ServiceRendezVous;

// nouveaux imports pour patient
use toubilib\core\application\ports\spi\repositoryInterfaces\PatientRepositoryInterface;
use toubilib\infra\repositories\PDOPatientRepository;
use toubilib\core\application\usecases\ServicePatient;

return [
        // service
    ServicePraticienInterface::class => function (ContainerInterface $c) {
        return new ServicePraticien($c->get(PDOPraticienRepository::class));
    },
        // service rendez-vous
    ServiceRendezVousInterface::class => function (ContainerInterface $c) {
        return new ServiceRendezVous($c->get(RdvRepositoryInterface::class));
    },

        // service patient
 
    ServicePatientInterface::class => function (ContainerInterface $c) {
        return new ServicePatient($c->get(PatientRepositoryInterface::class));
    },



    // infra
    'toubiprat.pdo' => function (ContainerInterface $c) {
        $config = parse_ini_file($c->get('toubiprat.db.config'));
        $dsn = "{$config['driver']}:host={$config['host']};dbname={$config['database']}";
        $user = $config['username'];
        $password = $config['password'];
        return new \PDO($dsn, $user, $password, [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
    },

    'toubiauth.pdo' => function (ContainerInterface $c) {
        $config = parse_ini_file($c->get('toubiauth.db.config'));
        $dsn = "{$config['driver']}:host={$config['host']};dbname={$config['database']}";
        $user = $config['username'];
        $password = $config['password'];
        return new \PDO($dsn, $user, $password, [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
    },

    'toubirdv.pdo' => function (ContainerInterface $c) {
        $config = parse_ini_file($c->get('toubirdv.db.config'));
        $dsn = "{$config['driver']}:host={$config['host']};dbname={$config['database']}";
        $user = $config['username'];
        $password = $config['password'];
        return new \PDO($dsn, $user, $password, [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
    },

    'toubipat.pdo' => function (ContainerInterface $c) {
        $config = parse_ini_file($c->get('toubipat.db.config'));
        $dsn = "{$config['driver']}:host={$config['host']};dbname={$config['database']}";
        $user = $config['username'];
        $password = $config['password'];
        return new \PDO($dsn, $user, $password, [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
    },

    PDOPraticienRepository::class => fn(ContainerInterface $c) => new PDOPraticienRepository($c->get('toubiprat.pdo')),
        // rdv infra
    RdvRepositoryInterface::class => fn(ContainerInterface $c) => new PgRdvRepository($c->get('toubirdv.pdo')),

        // mapping concret pour PDOPatientRepository (optionnel mais pratique)
    PDOPatientRepository::class => fn(ContainerInterface $c) => new PDOPatientRepository($c->get('toubipat.pdo')),
    PatientRepositoryInterface::class => fn(ContainerInterface $c) => new PDOPatientRepository($c->get('toubipat.pdo')),
];