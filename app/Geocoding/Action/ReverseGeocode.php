<?php
declare(strict_types=1);
namespace UMA\PakDiscounts\Geocoding\Action;
use Doctrine\ORM\EntityManager;
use Slim\Psr7;
use stdClass;
class ReverseGeocode
{
    /**
     * @var EntityManager
     */
    private $em;
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    public function __invoke(Psr7\Request $request, Psr7\Response $response): Psr7\Response
    {
        //read geocode from input
        $params = $request->getQueryParams();
        $qb = $this->em->createQueryBuilder();

        $cityPolygon = 'gc.geom';
        $queryPoint = 'Geometry(ST_SetSRID(ST_Point(:longitude, :latitude), 4326))';
        $qb
            ->select('gc')
            ->from('UMA\PakDiscounts\Domain\GeoCity', 'gc')
            ->where( $qb->expr()->eq("ST_Contains($cityPolygon, $queryPoint)", $qb->expr()->literal(true) ) )
            ->setParameter('latitude', $params['lat'])
            ->setParameter('longitude', $params['long'])
        ;
        $result = new stdClass();
        $result->city = $qb
        ->setMaxResults(1)
        ->getQuery()
        ->getOneOrNullResult();
        
        $qb = $this->em->createQueryBuilder();
        $areaPolygon = 'ga.geom';
        $qb
            ->select('ga')
            ->from('UMA\PakDiscounts\Domain\GeoArea', 'ga')
            ->where( $qb->expr()->eq("ST_Contains($areaPolygon, $queryPoint)", $qb->expr()->literal(true) ) )
            ->setParameter('latitude', $params['lat'])
            ->setParameter('longitude', $params['long'])
        ;
        $result->area = $qb
        ->setMaxResults(1)
        ->getQuery()
        ->getOneOrNullResult();

        $qb = $this->em->createQueryBuilder();
        $nbPolygon = 'sa.geom';
        $qb
            ->select('sa')
            ->from('UMA\PakDiscounts\Domain\GeoSubArea', 'sa')
            ->where( $qb->expr()->eq("ST_Contains($nbPolygon, $queryPoint)", $qb->expr()->literal(true) ) )
            ->setParameter('latitude', $params['lat'])
            ->setParameter('longitude', $params['long'])
        ;
        $result->sub_area = $qb
        ->setMaxResults(1)
        ->getQuery()
        ->getOneOrNullResult();

        $qb = $this->em->createQueryBuilder();
        $nbPolygon = 'gl.geom';
        $qb
            ->select('gl')
            ->from('UMA\PakDiscounts\Domain\GeoLandmark', 'gl')
            ->where( $qb->expr()->eq("ST_Contains($nbPolygon, $queryPoint)", $qb->expr()->literal(true) ) )
            ->setParameter('latitude', $params['lat'])
            ->setParameter('longitude', $params['long'])
        ;
        $result->landmark = $qb
        ->setMaxResults(1)
        ->getQuery()
        ->getOneOrNullResult();
        


        $body = $response->getBody();
        $body->write(json_encode($result));
        return $response
                    ->withStatus(200)
                    ->withHeader('Content-Type', 'application/json')
                    ->withHeader('Content-Length', $body->getSize())
                    ->withBody($body);
        //query at GeoCities
        //query at GeoAreas
    }
}