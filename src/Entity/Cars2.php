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
 * Entity class for "cars2" table
 */
#[Entity]
#[Table(name: "cars2")]
class Cars2 extends AbstractEntity
{
    public static array $propertyNames = [
        'ID' => 'id',
        'Trademark' => 'trademark',
        'Model' => 'model',
        'HP' => 'hp',
        'Cylinders' => 'cylinders',
        'Transmission Speeds' => 'transmissionSpeeds',
        'TransmissAutomatic' => 'transmissAutomatic',
        'MPG City' => 'mpgCity',
        'MPG Highway' => 'mpgHighway',
        'Description' => 'description',
        'Price' => 'price',
        'Picture' => 'picture',
        'Doors' => 'doors',
        'Torque' => 'torque',
    ];

    #[Id]
    #[Column(name: "ID", type: "integer")]
    #[GeneratedValue]
    private int $id;

    #[Column(name: "Trademark", type: "integer", nullable: true)]
    private ?int $trademark = 0;

    #[Column(name: "Model", type: "integer", nullable: true)]
    private ?int $model;

    #[Column(name: "HP", type: "string", nullable: true)]
    private ?string $hp;

    #[Column(name: "Cylinders", type: "integer", nullable: true)]
    private ?int $cylinders;

    #[Column(name: "`Transmission Speeds`", type: "string", nullable: true)]
    private ?string $transmissionSpeeds;

    #[Column(name: "TransmissAutomatic", type: "boolean", nullable: true)]
    private ?bool $transmissAutomatic = false;

    #[Column(name: "`MPG City`", type: "integer", nullable: true)]
    private ?int $mpgCity;

    #[Column(name: "`MPG Highway`", type: "integer", nullable: true)]
    private ?int $mpgHighway;

    #[Column(name: "Description", type: "text", nullable: true)]
    private ?string $description;

    #[Column(name: "Price", type: "float", nullable: true)]
    private ?float $price;

    #[Column(name: "Picture", type: "blob", nullable: true)]
    private mixed $picture;

    #[Column(name: "Doors", type: "integer", nullable: true)]
    private ?int $doors;

    #[Column(name: "Torque", type: "string", nullable: true)]
    private ?string $torque;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $value): static
    {
        $this->id = $value;
        return $this;
    }

    public function getTrademark(): ?int
    {
        return $this->trademark;
    }

    public function setTrademark(?int $value): static
    {
        $this->trademark = $value;
        return $this;
    }

    public function getModel(): ?int
    {
        return $this->model;
    }

    public function setModel(?int $value): static
    {
        $this->model = $value;
        return $this;
    }

    public function getHp(): ?string
    {
        return HtmlDecode($this->hp);
    }

    public function setHp(?string $value): static
    {
        $this->hp = RemoveXss($value);
        return $this;
    }

    public function getCylinders(): ?int
    {
        return $this->cylinders;
    }

    public function setCylinders(?int $value): static
    {
        $this->cylinders = $value;
        return $this;
    }

    public function getTransmissionSpeeds(): ?string
    {
        return HtmlDecode($this->transmissionSpeeds);
    }

    public function setTransmissionSpeeds(?string $value): static
    {
        $this->transmissionSpeeds = RemoveXss($value);
        return $this;
    }

    public function getTransmissAutomatic(): ?bool
    {
        return $this->transmissAutomatic;
    }

    public function setTransmissAutomatic(?bool $value): static
    {
        $this->transmissAutomatic = $value;
        return $this;
    }

    public function getMpgCity(): ?int
    {
        return $this->mpgCity;
    }

    public function setMpgCity(?int $value): static
    {
        $this->mpgCity = $value;
        return $this;
    }

    public function getMpgHighway(): ?int
    {
        return $this->mpgHighway;
    }

    public function setMpgHighway(?int $value): static
    {
        $this->mpgHighway = $value;
        return $this;
    }

    public function getDescription(): ?string
    {
        return HtmlDecode($this->description);
    }

    public function setDescription(?string $value): static
    {
        $this->description = RemoveXss($value);
        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $value): static
    {
        $this->price = $value;
        return $this;
    }

    public function getPicture(): mixed
    {
        return $this->picture;
    }

    public function setPicture(mixed $value): static
    {
        $this->picture = $value;
        return $this;
    }

    public function getDoors(): ?int
    {
        return $this->doors;
    }

    public function setDoors(?int $value): static
    {
        $this->doors = $value;
        return $this;
    }

    public function getTorque(): ?string
    {
        return HtmlDecode($this->torque);
    }

    public function setTorque(?string $value): static
    {
        $this->torque = RemoveXss($value);
        return $this;
    }
}
