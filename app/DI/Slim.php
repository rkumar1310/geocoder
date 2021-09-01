<?php

declare(strict_types=1);

namespace UMA\PakDiscounts\DI;

use Doctrine\ORM\EntityManager;
use Faker;
use Slim\App;
use Slim\Factory\AppFactory;
use UMA\DIC\Container;
use UMA\DIC\ServiceProvider;
use UMA\PakDiscounts\Geocoding\Action\ReverseGeocode;

/**
 * A ServiceProvider for registering services related
 * to Slim such as request handlers, routing and the
 * App service itself.
 */
class Slim implements ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function provide(Container $c): void
    {
        $c->set(ReverseGeocode::class, static function(Container $c): ReverseGeocode {
            return new ReverseGeocode(
                $c->get(EntityManager::class)
            );
        });

        $c->set(App::class, static function (Container $c): App {
            /** @var array $settings */
            $settings = $c->get('settings');

            $app = AppFactory::create(null, $c);

            $app->addErrorMiddleware(
                $settings['slim']['displayErrorDetails'],
                $settings['slim']['logErrors'],
                $settings['slim']['logErrorDetails']
            );

            $app->get('/reverse-geocode', ReverseGeocode::class);

            return $app;
        });
    }
}