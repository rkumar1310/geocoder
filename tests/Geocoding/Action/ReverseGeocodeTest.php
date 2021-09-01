<?php

declare(strict_types=1);

namespace Tests\Geocoding\Action;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Tests\TestCase;
use UMA\PakDiscounts\Geocoding\Action\ReverseGeocode;

class ReverseGeocodeTest extends TestCase
{
    public function testActionSetsHttpCodeInRespond()
    {
        $app = $this->getAppInstance();
        $container = $app->getContainer();
        $mockedEm = $this->createMock(EntityManager::class);
        $mockedQB = $this->createMock(QueryBuilder::class);
        $mockedExpr = $this->createMock(Expr::class);
        $mockedQuery = $this->createMock(AbstractQuery::class);
        $request = $this->createMock(Request::class);
        $response = $this->createMock(Response::class);
        $body = $this->createMock(\Psr\Http\Message\StreamInterface::class);

        $mockedEm->method('createQueryBuilder')->willReturn($mockedQB);
        $mockedQB->method('select')->willReturn($mockedQB);
        $mockedQB->method('from')->willReturn($mockedQB);
        $mockedQB->method('where')->willReturn($mockedQB);
        $mockedQB->method('expr')->willReturn($mockedExpr);
        $mockedQB->method('setParameter')->willReturn($mockedQB);
        $mockedQB->method('setMaxResults')->willReturn($mockedQB);
        $mockedQB->method('getQuery')->willReturn($mockedQuery);

        $mockedQuery->expects($this->exactly(4))->method('getOneOrNullResult')
            ->willReturnOnConsecutiveCalls(
                (object)['name' => 'some city'],
                (object)['name' => 'some area'],
                (object)['name' => 'some sub area'],
                (object)['name' => 'some landmark']
            );


        $reverseGeocoder = new ReverseGeocode($mockedEm);

        $request->method('getQueryParams')->willReturn(['lat' => 10, 'long' => 10]);
        $response->method('withStatus')->with(200)->willReturn($response);
        $response->method('getBody')->willReturn($body);
        $response->expects($this->exactly(2))->method('withHeader')->willReturn($response);
        $response->expects($this->once())->method('withBody')->with($body)->willReturn($response);
        $body->expects($this->once())->method('write')
            ->with('{"city":{"name":"some city"},"area":{"name":"some area"},"sub_area":{"name":"some sub area"}'.
                ',"landmark":{"name":"some landmark"}}');



        $reverseGeocoder->__invoke($request, $response);
    }
}
