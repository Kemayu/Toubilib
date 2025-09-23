<?php

use Psr\Container\ContainerInterface;
use jira\api\actions\GetAllUserStoriesAction;
use jira\core\application\ports\spi\UserStoryRepository;
use jira\core\application\ports\api\UserStoryServiceInterface;
use jira\core\application\usecases\UserStoryService;
use jira\infra\repositories\PgUserStoryRepository;
use toubilib\api\actions\PraticienAction;
use toubilib\core\application\ports\spi\repositoryInterfaces\ServicePraticienInterface;
use toubilib\core\application\usecases\ServicePraticien;
use toubilib\infra\repositories\PDOPraticienRepository;

return [

    // settings
    'displayErrorDetails' => true,
    'logs.dir' => __DIR__ . '/../var/logs',
    'toubilib.db.config' => __DIR__ . '/toubilib.db.ini',
    
    // application
    PraticienAction::class=> function (ContainerInterface $c) {
        return new PraticienAction($c->get(ServicePraticienInterface::class));
    },

    // service
    ServicePraticienInterface::class => function (ContainerInterface $c) {
        return new ServicePraticien($c->get(PDOPraticienRepository::class));
    },

    // infra
     'toubilib.pdo' => function (ContainerInterface $c) {
        $config = parse_ini_file($c->get('toubilib.db.config'));
        $dsn = "{$config['driver']}:host={$config['host']};dbname={$config['database']}";
        $user = $config['username'];
        $password = $config['password'];
        return new \PDO($dsn, $user, $password, [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
    },

    PDOPraticienRepository::class => fn(ContainerInterface $c) => new PDOPraticienRepository($c->get('toubilib.pdo')),
    
];

