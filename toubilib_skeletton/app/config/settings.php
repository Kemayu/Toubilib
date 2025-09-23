<?php

use Psr\Container\ContainerInterface;
use jira\api\actions\GetAllUserStoriesAction;
use jira\core\application\ports\spi\UserStoryRepository;
use jira\core\application\ports\api\UserStoryServiceInterface;
use jira\core\application\usecases\UserStoryService;
use jira\infra\repositories\PgUserStoryRepository;
use toubilib\api\actions\ListerPraticienAction;
use toubilib\api\actions\PraticienAction;
use toubilib\core\application\ports\spi\repositoryInterfaces\ServicePraticienInterface;
use toubilib\core\application\usecases\ServicePraticien;
use toubilib\infra\repositories\PDOPraticienRepository;

return [

    // settings
    'displayErrorDetails' => true,
    'logs.dir' => __DIR__ . '/../var/logs',
    'toubilib.db.config' => __DIR__ . '/toubilib.db.ini',
    
   
];

