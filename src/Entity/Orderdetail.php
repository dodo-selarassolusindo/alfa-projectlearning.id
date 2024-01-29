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
 * Entity class for "orderdetails" table
 */
#[Entity]
#[Table(name: "orderdetails")]
class Orderdetail extends AbstractEntity
{
    public static array $propertyNames = [
        'OrderID' => 'orderId',
        'ProductID' => 'productId',
        'UnitPrice' => 'unitPrice',
        'Quantity' => 'quantity',
        'Discount' => 'discount',
    ];

    #[Id]
    #[Column(name: "OrderID", type: "integer")]
    private int $orderId;

    #[Id]
    #[Column(name: "ProductID", type: "integer")]
    private int $productId;

    #[Column(name: "UnitPrice", type: "float")]
    private float $unitPrice = 0;

    #[Column(name: "Quantity", type: "smallint")]
    private int $quantity = 1;

    #[Column(name: "Discount", type: "float")]
    private float $discount = 0;

    public function __construct(int $orderId, int $productId)
    {
        $this->orderId = $orderId;
        $this->productId = $productId;
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

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function setProductId(int $value): static
    {
        $this->productId = $value;
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
}
