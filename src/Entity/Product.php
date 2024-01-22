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
 * Entity class for "products" table
 */
#[Entity]
#[Table(name: "products")]
class Product extends AbstractEntity
{
    public static array $propertyNames = [
        'ProductID' => 'productId',
        'ProductName' => 'productName',
        'SupplierID' => 'supplierId',
        'CategoryID' => 'categoryId',
        'QuantityPerUnit' => 'quantityPerUnit',
        'UnitPrice' => 'unitPrice',
        'UnitsInStock' => 'unitsInStock',
        'UnitsOnOrder' => 'unitsOnOrder',
        'ReorderLevel' => 'reorderLevel',
        'Discontinued' => 'discontinued',
        'EAN13' => 'ean13',
    ];

    #[Id]
    #[Column(name: "ProductID", type: "integer", unique: true)]
    #[GeneratedValue]
    private int $productId;

    #[Column(name: "ProductName", type: "string")]
    private string $productName;

    #[Column(name: "SupplierID", type: "integer", nullable: true)]
    private ?int $supplierId;

    #[Column(name: "CategoryID", type: "integer", nullable: true)]
    private ?int $categoryId;

    #[Column(name: "QuantityPerUnit", type: "string", nullable: true)]
    private ?string $quantityPerUnit;

    #[Column(name: "UnitPrice", type: "float", nullable: true)]
    private ?float $unitPrice = 0;

    #[Column(name: "UnitsInStock", type: "smallint", nullable: true)]
    private ?int $unitsInStock = 0;

    #[Column(name: "UnitsOnOrder", type: "smallint", nullable: true)]
    private ?int $unitsOnOrder = 0;

    #[Column(name: "ReorderLevel", type: "smallint", nullable: true)]
    private ?int $reorderLevel = 0;

    #[Column(name: "Discontinued", type: "boolean", nullable: true)]
    private ?bool $discontinued = false;

    #[Column(name: "EAN13", type: "string", nullable: true)]
    private ?string $ean13;

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function setProductId(int $value): static
    {
        $this->productId = $value;
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

    public function getSupplierId(): ?int
    {
        return $this->supplierId;
    }

    public function setSupplierId(?int $value): static
    {
        $this->supplierId = $value;
        return $this;
    }

    public function getCategoryId(): ?int
    {
        return $this->categoryId;
    }

    public function setCategoryId(?int $value): static
    {
        $this->categoryId = $value;
        return $this;
    }

    public function getQuantityPerUnit(): ?string
    {
        return HtmlDecode($this->quantityPerUnit);
    }

    public function setQuantityPerUnit(?string $value): static
    {
        $this->quantityPerUnit = RemoveXss($value);
        return $this;
    }

    public function getUnitPrice(): ?float
    {
        return $this->unitPrice;
    }

    public function setUnitPrice(?float $value): static
    {
        $this->unitPrice = $value;
        return $this;
    }

    public function getUnitsInStock(): ?int
    {
        return $this->unitsInStock;
    }

    public function setUnitsInStock(?int $value): static
    {
        $this->unitsInStock = $value;
        return $this;
    }

    public function getUnitsOnOrder(): ?int
    {
        return $this->unitsOnOrder;
    }

    public function setUnitsOnOrder(?int $value): static
    {
        $this->unitsOnOrder = $value;
        return $this;
    }

    public function getReorderLevel(): ?int
    {
        return $this->reorderLevel;
    }

    public function setReorderLevel(?int $value): static
    {
        $this->reorderLevel = $value;
        return $this;
    }

    public function getDiscontinued(): ?bool
    {
        return $this->discontinued;
    }

    public function setDiscontinued(?bool $value): static
    {
        $this->discontinued = $value;
        return $this;
    }

    public function getEan13(): ?string
    {
        return HtmlDecode($this->ean13);
    }

    public function setEan13(?string $value): static
    {
        $this->ean13 = RemoveXss($value);
        return $this;
    }
}
