<?php

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use toubilib\api\middlewares\Cors;

$envFile = __DIR__ . '/.env';
$envDist = __DIR__ . '/.env.dist';

try {
    if (file_exists($envFile)) {
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->load();
    } elseif (file_exists($envDist)) {
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__, '.env.dist');
        $dotenv->load();
    } else {
        // Aucun .env trouvé — continuer avec les valeurs d'environnement existantes
    }
} catch (\Dotenv\Exception\InvalidPathException $e) {
    // ignorer l'erreur et continuer (ou logger si besoin)
}

$app = AppFactory::create();

$app->addBodyParsingMiddleware();
$app->add(Cors::class);
$app->addRoutingMiddleware();

// Récupère displayErrorDetails depuis l'environnement (ex: DISPLAY_ERROR_DETAILS=true)
// fallback : false
$displayErrorDetails = false;
$envValue = getenv('DISPLAY_ERROR_DETAILS');
if ($envValue !== false) {
    $displayErrorDetails = filter_var($envValue, FILTER_VALIDATE_BOOLEAN);
}

$app->addErrorMiddleware($displayErrorDetails, false, false)
    ->getDefaultErrorHandler()
    ->forceContentType('application/json');

$routes = require_once __DIR__ . '/../src/api/routes.php';
if (is_callable($routes)) {
    $app = $routes($app);
}

return $app;