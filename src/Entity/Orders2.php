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
 * Entity class for "orders2" table
 */
#[Entity]
#[Table(name: "orders2")]
class Orders2 extends AbstractEntity
{
    public static array $propertyNames = [
        'OrderID' => 'orderId',
        'CustomerID' => 'customerId',
        'EmployeeID' => 'employeeId',
        'OrderDate' => 'orderDate',
        'RequiredDate' => 'requiredDate',
        'ShippedDate' => 'shippedDate',
        'ShipVia' => 'shipVia',
        'Freight' => 'freight',
        'ShipName' => 'shipName',
        'ShipAddress' => 'shipAddress',
        'ShipCity' => 'shipCity',
        'ShipRegion' => 'shipRegion',
        'ShipPostalCode' => 'shipPostalCode',
        'ShipCountry' => 'shipCountry',
    ];

    #[Id]
    #[Column(name: "OrderID", type: "integer")]
    #[GeneratedValue]
    private int $orderId;

    #[Column(name: "CustomerID", type: "string", nullable: true)]
    private ?string $customerId;

    #[Column(name: "EmployeeID", type: "integer", nullable: true)]
    private ?int $employeeId;

    #[Column(name: "OrderDate", type: "datetime", nullable: true)]
    private ?DateTime $orderDate;

    #[Column(name: "RequiredDate", type: "datetime", nullable: true)]
    private ?DateTime $requiredDate;

    #[Column(name: "ShippedDate", type: "datetime", nullable: true)]
    private ?DateTime $shippedDate;

    #[Column(name: "ShipVia", type: "integer", nullable: true)]
    private ?int $shipVia;

    #[Column(name: "Freight", type: "float", nullable: true)]
    private ?float $freight = 0;

    #[Column(name: "ShipName", type: "string", nullable: true)]
    private ?string $shipName;

    #[Column(name: "ShipAddress", type: "string", nullable: true)]
    private ?string $shipAddress;

    #[Column(name: "ShipCity", type: "string", nullable: true)]
    private ?string $shipCity;

    #[Column(name: "ShipRegion", type: "string", nullable: true)]
    private ?string $shipRegion;

    #[Column(name: "ShipPostalCode", type: "string", nullable: true)]
    private ?string $shipPostalCode;

    #[Column(name: "ShipCountry", type: "string", nullable: true)]
    private ?string $shipCountry;

    public function getOrderId(): int
    {
        return $this->orderId;
    }

    public function setOrderId(int $value): static
    {
        $this->orderId = $value;
        return $this;
    }

    public function getCustomerId(): ?string
    {
        return HtmlDecode($this->customerId);
    }

    public function setCustomerId(?string $value): static
    {
        $this->customerId = RemoveXss($value);
        return $this;
    }

    public function getEmployeeId(): ?int
    {
        return $this->employeeId;
    }

    public function setEmployeeId(?int $value): static
    {
        $this->employeeId = $value;
        return $this;
    }

    public function getOrderDate(): ?DateTime
    {
        return $this->orderDate;
    }

    public function setOrderDate(?DateTime $value): static
    {
        $this->orderDate = $value;
        return $this;
    }

    public function getRequiredDate(): ?DateTime
    {
        return $this->requiredDate;
    }

    public function setRequiredDate(?DateTime $value): static
    {
        $this->requiredDate = $value;
        return $this;
    }

    public function getShippedDate(): ?DateTime
    {
        return $this->shippedDate;
    }

    public function setShippedDate(?DateTime $value): static
    {
        $this->shippedDate = $value;
        return $this;
    }

    public function getShipVia(): ?int
    {
        return $this->shipVia;
    }

    public function setShipVia(?int $value): static
    {
        $this->shipVia = $value;
        return $this;
    }

    public function getFreight(): ?float
    {
        return $this->freight;
    }

    public function setFreight(?float $value): static
    {
        $this->freight = $value;
        return $this;
    }

    public function getShipName(): ?string
    {
        return HtmlDecode($this->shipName);
    }

    public function setShipName(?string $value): static
    {
        $this->shipName = RemoveXss($value);
        return $this;
    }

    public function getShipAddress(): ?string
    {
        return HtmlDecode($this->shipAddress);
    }

    public function setShipAddress(?string $value): static
    {
        $this->shipAddress = RemoveXss($value);
        return $this;
    }

    public function getShipCity(): ?string
    {
        return HtmlDecode($this->shipCity);
    }

    public function setShipCity(?string $value): static
    {
        $this->shipCity = RemoveXss($value);
        return $this;
    }

    public function getShipRegion(): ?string
    {
        return HtmlDecode($this->shipRegion);
    }

    public function setShipRegion(?string $value): static
    {
        $this->shipRegion = RemoveXss($value);
        return $this;
    }

    public function getShipPostalCode(): ?string
    {
        return HtmlDecode($this->shipPostalCode);
    }

    public function setShipPostalCode(?string $value): static
    {
        $this->shipPostalCode = RemoveXss($value);
        return $this;
    }

    public function getShipCountry(): ?string
    {
        return HtmlDecode($this->shipCountry);
    }

    public function setShipCountry(?string $value): static
    {
        $this->shipCountry = RemoveXss($value);
        return $this;
    }
}
