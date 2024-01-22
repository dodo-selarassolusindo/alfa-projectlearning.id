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
 * Entity class for "dji" table
 */
#[Entity]
#[Table(name: "dji")]
class Dji extends AbstractEntity
{
    public static array $propertyNames = [
        'ID' => 'id',
        'Date' => 'date',
        'Open' => 'open',
        'High' => 'high',
        'Low' => 'low',
        'Close' => 'close',
        'Volume' => 'volume',
        'Adj Close' => 'adjClose',
        'Name' => 'name',
        'Name2' => 'name2',
    ];

    #[Id]
    #[Column(name: "ID", type: "integer", unique: true)]
    #[GeneratedValue]
    private int $id;

    #[Column(name: "Date", type: "datetime", nullable: true)]
    private ?DateTime $date;

    #[Column(name: "Open", type: "float", nullable: true)]
    private ?float $open;

    #[Column(name: "High", type: "float", nullable: true)]
    private ?float $high;

    #[Column(name: "Low", type: "float", nullable: true)]
    private ?float $low;

    #[Column(name: "Close", type: "float", nullable: true)]
    private ?float $close;

    #[Column(name: "Volume", type: "float", nullable: true)]
    private ?float $volume;

    #[Column(name: "`Adj Close`", type: "float", nullable: true)]
    private ?float $adjClose;

    #[Column(name: "Name", type: "datetime", nullable: true)]
    private ?DateTime $name;

    #[Column(name: "Name2", type: "string", nullable: true)]
    private ?string $name2;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $value): static
    {
        $this->id = $value;
        return $this;
    }

    public function getDate(): ?DateTime
    {
        return $this->date;
    }

    public function setDate(?DateTime $value): static
    {
        $this->date = $value;
        return $this;
    }

    public function getOpen(): ?float
    {
        return $this->open;
    }

    public function setOpen(?float $value): static
    {
        $this->open = $value;
        return $this;
    }

    public function getHigh(): ?float
    {
        return $this->high;
    }

    public function setHigh(?float $value): static
    {
        $this->high = $value;
        return $this;
    }

    public function getLow(): ?float
    {
        return $this->low;
    }

    public function setLow(?float $value): static
    {
        $this->low = $value;
        return $this;
    }

    public function getClose(): ?float
    {
        return $this->close;
    }

    public function setClose(?float $value): static
    {
        $this->close = $value;
        return $this;
    }

    public function getVolume(): ?float
    {
        return $this->volume;
    }

    public function setVolume(?float $value): static
    {
        $this->volume = $value;
        return $this;
    }

    public function getAdjClose(): ?float
    {
        return $this->adjClose;
    }

    public function setAdjClose(?float $value): static
    {
        $this->adjClose = $value;
        return $this;
    }

    public function getName(): ?DateTime
    {
        return $this->name;
    }

    public function setName(?DateTime $value): static
    {
        $this->name = $value;
        return $this;
    }

    public function getName2(): ?string
    {
        return HtmlDecode($this->name2);
    }

    public function setName2(?string $value): static
    {
        $this->name2 = RemoveXss($value);
        return $this;
    }
}
