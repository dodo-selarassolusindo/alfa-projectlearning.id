<?php

namespace PHPMaker2024\prj_alfa\Entity;

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
use PHPMaker2024\prj_alfa\AbstractEntity;
use PHPMaker2024\prj_alfa\AdvancedSecurity;
use PHPMaker2024\prj_alfa\UserProfile;
use function PHPMaker2024\prj_alfa\Config;
use function PHPMaker2024\prj_alfa\EntityManager;
use function PHPMaker2024\prj_alfa\RemoveXss;
use function PHPMaker2024\prj_alfa\HtmlDecode;
use function PHPMaker2024\prj_alfa\EncryptPassword;

/**
 * Entity class for "models" table
 */
#[Entity]
#[Table(name: "models")]
class Model extends AbstractEntity
{
    public static array $propertyNames = [
        'ID' => 'id',
        'Trademark' => 'trademark',
        'Model' => 'model',
    ];

    #[Id]
    #[Column(name: "ID", type: "integer", unique: true)]
    #[GeneratedValue]
    private int $id;

    #[Column(name: "Trademark", type: "integer", nullable: true)]
    private ?int $trademark = 0;

    #[Column(name: "Model", type: "string", nullable: true)]
    private ?string $model;

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

    public function getModel(): ?string
    {
        return HtmlDecode($this->model);
    }

    public function setModel(?string $value): static
    {
        $this->model = RemoveXss($value);
        return $this;
    }
}
