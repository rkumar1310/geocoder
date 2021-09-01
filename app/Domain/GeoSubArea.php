<?php

declare(strict_types=1);

namespace UMA\PakDiscounts\Domain;

/**
 * GeoNeighbourhood : A neighbourhood representation
 *
 * @Entity()
 * @Table(name="geo_sub_areas")
 */
class GeoSubArea implements \JsonSerializable
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
     * @var \Jsor\Doctrine\PostGIS\Types\GeometryType
     *
     * @Column(type="geometry", options={"geometry_type"="MULTIPOLYGON"})
     */
    private $geom;

    public function __construct(string $name, string $geomString)
    {
        $this->name = $name;
        $this->geom = $geomString;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
    public function getGeom(): \Jsor\Doctrine\PostGIS\Types\GeometryType
    {
        return $this->geom;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return [
            'name' => $this->getName(),
        ];
    }
}