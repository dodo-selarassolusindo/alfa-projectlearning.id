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
 * Entity class for "materi" table
 */
#[Entity]
#[Table(name: "materi")]
class Materi extends AbstractEntity
{
    public static array $propertyNames = [
        'id' => 'id',
        'Nama' => 'nama',
        'Tipe' => 'tipe',
    ];

    #[Id]
    #[Column(type: "integer", unique: true)]
    #[GeneratedValue]
    private int $id;

    #[Column(name: "Nama", type: "string")]
    private string $nama;

    #[Column(name: "Tipe", type: "string")]
    private string $tipe;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $value): static
    {
        $this->id = $value;
        return $this;
    }

    public function getNama(): string
    {
        return HtmlDecode($this->nama);
    }

    public function setNama(string $value): static
    {
        $this->nama = RemoveXss($value);
        return $this;
    }

    public function getTipe(): string
    {
        return $this->tipe;
    }

    public function setTipe(string $value): static
    {
        if (!in_array($value, ["Free", "Premium"])) {
            throw new \InvalidArgumentException("Invalid 'Tipe' value");
        }
        $this->tipe = $value;
        return $this;
    }
}
