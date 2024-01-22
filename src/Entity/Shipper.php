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
 * Entity class for "shippers" table
 */
#[Entity]
#[Table(name: "shippers")]
class Shipper extends AbstractEntity
{
    public static array $propertyNames = [
        'ShipperID' => 'shipperId',
        'CompanyName' => 'companyName',
        'Phone' => 'phone',
    ];

    #[Id]
    #[Column(name: "ShipperID", type: "integer", unique: true)]
    #[GeneratedValue]
    private int $shipperId;

    #[Column(name: "CompanyName", type: "string")]
    private string $companyName;

    #[Column(name: "Phone", type: "string", nullable: true)]
    private ?string $phone;

    public function getShipperId(): int
    {
        return $this->shipperId;
    }

    public function setShipperId(int $value): static
    {
        $this->shipperId = $value;
        return $this;
    }

    public function getCompanyName(): string
    {
        return HtmlDecode($this->companyName);
    }

    public function setCompanyName(string $value): static
    {
        $this->companyName = RemoveXss($value);
        return $this;
    }

    public function getPhone(): ?string
    {
        return HtmlDecode($this->phone);
    }

    public function setPhone(?string $value): static
    {
        $this->phone = RemoveXss($value);
        return $this;
    }
}
