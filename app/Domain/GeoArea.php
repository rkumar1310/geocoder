<?php

declare(strict_types=1);

namespace UMA\PakDiscounts\Domain;


/**
 * GeoArea : A small geographical area
 *
 * @Entity()
 * @Table(name="geo_areas")
 */
class GeoArea implements \JsonSerializable
{
    /**
     * @var int
     *
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @Column(type="string", nullable=false, unique=true)
     */
    private $name;

    /**
     * @var \Jsor\Doctrine\PostGIS\Functions\GeometryType
     *
     * @Column(type="geometry", options={"geometry_type"="MULTIPOLYGON"})
     */
    private $geom;

    public function __construct(string $name, string $polygonString)
    {
        $this->name = $name;
        $this->polygon = $polygonString;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
    public function getGeom(): \Jsor\Doctrine\PostGIS\Functions\GeometryType
    {
        return $this->geom;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return [
            'name' => $this->getName()
        ];
    }
}