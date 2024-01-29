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
 * Entity class for "trademarks" table
 */
#[Entity]
#[Table(name: "trademarks")]
class Trademark extends AbstractEntity
{
    public static array $propertyNames = [
        'ID' => 'id',
        'Trademark' => 'trademark',
    ];

    #[Id]
    #[Column(name: "ID", type: "integer", unique: true)]
    #[GeneratedValue]
    private int $id;

    #[Column(name: "Trademark", type: "string", nullable: true)]
    private ?string $trademark;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $value): static
    {
        $this->id = $value;
        return $this;
    }

    public function getTrademark(): ?string
    {
        return HtmlDecode($this->trademark);
    }

    public function setTrademark(?string $value): static
    {
        $this->trademark = RemoveXss($value);
        return $this;
    }
}
