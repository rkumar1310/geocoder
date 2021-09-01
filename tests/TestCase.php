<?php

declare(strict_types=1);

namespace Tests;

use Exception;
use PHPUnit\Framework\TestCase as PHPUnit_TestCase;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Factory\ServerRequestCreatorFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class TestCase extends PHPUnit_TestCase
{
    /**
     * @return App
     * @throws Exception
     */
    protected function getAppInstance(): App
    {
        $container = require __DIR__ . '/../bootstrap.php';

        AppFactory::setSlimHttpDecoratorsAutomaticDetection(false);
        ServerRequestCreatorFactory::setSlimHttpDecoratorsAutomaticDetection(false);


        $container->register(new \UMA\PakDiscounts\DI\Doctrine());
        $container->register(new \UMA\PakDiscounts\DI\Slim());

        /** @var App $app */
        $app = $container->get(App::class);

        // Add error middleware
        $app->addErrorMiddleware(true, true, true);

        // Add Home Route
        $app->get('/', function (Request $request, Response $response) {
            $response->getBody()->write('GeoCoding Server');
            return $response;
        });

        return $app;
    }
}
