<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Factory\ServerRequestCreatorFactory;
use Slim\App;
use UMA\PakDiscounts\DI;

/** @var Container $cnt */
$cnt = require_once __DIR__ . '/../bootstrap.php';


AppFactory::setSlimHttpDecoratorsAutomaticDetection(false);
ServerRequestCreatorFactory::setSlimHttpDecoratorsAutomaticDetection(false);


$cnt->register(new DI\Doctrine());
$cnt->register(new DI\Slim());

/** @var App $app */
$app = $cnt->get(App::class);

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add Home Route
$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write('GeoCoding Server');
    return $response;
});

$app->run();