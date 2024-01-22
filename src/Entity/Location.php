<?php

namespace PHPMaker2024\demo2024\Entity;

use DateTime;
use DateTimeImmutable;
use DateInterval;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\SequenceGenerator;
use Doctrine\DBAL\Types\Types;
use PHPMaker2024\demo2024\AbstractEntity;
use PHPMaker2024\demo2024\AdvancedSecurity;
use PHPMaker2024\demo2024\UserProfile;
use function PHPMaker2024\demo2024\Config;
use function PHPMaker2024\demo2024\EntityManager;
use function PHPMaker2024\demo2024\RemoveXss;
use function PHPMaker2024\demo2024\HtmlDecode;
use function PHPMaker2024\demo2024\EncryptPassword;

/**
 * Entity class for "locations" table
 */
#[Entity]
#[Table(name: "locations")]
class Location extends AbstractEntity
{
    public static array $propertyNames = [
        'ID' => 'id',
        'Latitude' => 'latitude',
        'Longitude' => 'longitude',
        'Coordinate' => 'coordinate',
    ];

    #[Id]
    #[Column(name: "ID", type: "integer", unique: true)]
    #[GeneratedValue]
    private int $id;

    #[Column(name: "Latitude", type: "float")]
    private float $latitude = 0;

    #[Column(name: "Longitude", type: "float")]
    private float $longitude = 0;

    #[Column(name: "Coordinate", type: "geometry", nullable: true)]
    private ?string $coordinate;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $value): static
    {
        $this->id = $value;
        return $this;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function setLatitude(float $value): static
    {
        $this->latitude = $value;
        return $this;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function setLongitude(float $value): static
    {
        $this->longitude = $value;
        return $this;
    }

    public function getCoordinate(): ?string
    {
        return $this->coordinate;
    }

    public function setCoordinate(?string $value): static
    {
        $this->coordinate = $value;
        return $this;
    }
}
