<?php
declare(strict_types=1);
namespace UMA\PakDiscounts\DI;
use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use UMA\DIC\Container;
use UMA\DIC\ServiceProvider;
use Jsor\Doctrine\PostGIS\Event\ORMSchemaEventSubscriber;
use Doctrine\DBAL\Types\Type;

Type::addType('geometry', 'Jsor\Doctrine\PostGIS\Types\GeometryType');
Type::addType('geography', 'Jsor\Doctrine\PostGIS\Types\GeographyType');
Type::addType('raster', 'Jsor\Doctrine\PostGIS\Types\RasterType');

/**
 * A ServiceProvider for registering services related to
 * Doctrine in a DI container.
 *
 * If the project had custom repositories (e.g. UserRepository)
 * they could be registered here.
 */
class Doctrine implements ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function provide(Container $c): void
    {
        $c->set(EntityManager::class, static function (Container $c): EntityManager {
            /** @var array $settings */
            $settings = $c->get('settings');
            $ormConfiguration = Setup::createAnnotationMetadataConfiguration(
                $settings['doctrine']['metadata_dirs'],
                $settings['doctrine']['dev_mode'],
                null,
                $settings['doctrine']['dev_mode'] ? null : new FilesystemCache($settings['doctrine']['cache_dir'])
            );

            $ormConfiguration->addCustomStringFunction(
                'ST_Contains',
                'Jsor\Doctrine\PostGIS\Functions\ST_Contains'
            );

            $ormConfiguration->addCustomStringFunction(
                'Geometry',
                'Jsor\Doctrine\PostGIS\Functions\Geometry'
            );

            $ormConfiguration->addCustomStringFunction(
                'ST_SetSRID',
                'Jsor\Doctrine\PostGIS\Functions\ST_SetSRID'
            );

            $ormConfiguration->addCustomStringFunction(
                'ST_Point',
                'Jsor\Doctrine\PostGIS\Functions\ST_Point'
            );
            
            
            $em =  EntityManager::create(
                $settings['doctrine']['connection'],
                $ormConfiguration
            );

            $em->getEventManager()->addEventSubscriber(new ORMSchemaEventSubscriber());

            return $em;
        });
    }
}