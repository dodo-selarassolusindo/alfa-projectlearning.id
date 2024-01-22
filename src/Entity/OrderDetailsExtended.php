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
 * Entity class for "order details extended" table
 */
#[Entity]
#[Table(name: "`order details extended`")]
class OrderDetailsExtended extends AbstractEntity
{
    public static array $propertyNames = [
        'CompanyName' => 'companyName',
        'OrderID' => 'orderId',
        'ProductName' => 'productName',
        'UnitPrice' => 'unitPrice',
        'Quantity' => 'quantity',
        'Discount' => 'discount',
        'Extended Price' => 'extendedPrice',
    ];

    #[Column(name: "CompanyName", type: "string")]
    private string $companyName;

    #[Id]
    #[Column(name: "OrderID", type: "integer")]
    #[GeneratedValue]
    private int $orderId;

    #[Column(name: "ProductName", type: "string")]
    private string $productName;

    #[Column(name: "UnitPrice", type: "float")]
    private float $unitPrice = 0;

    #[Column(name: "Quantity", type: "smallint")]
    private int $quantity = 1;

    #[Column(name: "Discount", type: "float")]
    private float $discount = 0;

    #[Column(name: "`Extended Price`", type: "float")]
    private float $extendedPrice = 0;

    public function getCompanyName(): string
    {
        return HtmlDecode($this->companyName);
    }

    public function setCompanyName(string $value): static
    {
        $this->companyName = RemoveXss($value);
        return $this;
    }

    public function getOrderId(): int
    {
        return $this->orderId;
    }

    public function setOrderId(int $value): static
    {
        $this->orderId = $value;
        return $this;
    }

    public function getProductName(): string
    {
        return HtmlDecode($this->productName);
    }

    public function setProductName(string $value): static
    {
        $this->productName = RemoveXss($value);
        return $this;
    }

    public function getUnitPrice(): float
    {
        return $this->unitPrice;
    }

    public function setUnitPrice(float $value): static
    {
        $this->unitPrice = $value;
        return $this;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $value): static
    {
        $this->quantity = $value;
        return $this;
    }

    public function getDiscount(): float
    {
        return $this->discount;
    }

    public function setDiscount(float $value): static
    {
        $this->discount = $value;
        return $this;
    }

    public function getExtendedPrice(): float
    {
        return $this->extendedPrice;
    }

    public function setExtendedPrice(float $value): static
    {
        $this->extendedPrice = $value;
        return $this;
    }
}
