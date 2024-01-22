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
 * Entity class for "categories" table
 */
#[Entity]
#[Table(name: "categories")]
class Category extends AbstractEntity
{
    public static array $propertyNames = [
        'CategoryID' => 'categoryId',
        'CategoryName' => 'categoryName',
        'Description' => 'description',
        'Picture' => 'picture',
        'Icon_17' => 'icon17',
        'Icon_25' => 'icon25',
    ];

    #[Id]
    #[Column(name: "CategoryID", type: "integer", unique: true)]
    #[GeneratedValue]
    private int $categoryId;

    #[Column(name: "CategoryName", type: "string")]
    private string $categoryName;

    #[Column(name: "Description", type: "text", nullable: true)]
    private ?string $description;

    #[Column(name: "Picture", type: "blob", nullable: true)]
    private mixed $picture;

    #[Column(name: "Icon_17", type: "blob", nullable: true)]
    private mixed $icon17;

    #[Column(name: "Icon_25", type: "blob", nullable: true)]
    private mixed $icon25;

    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    public function setCategoryId(int $value): static
    {
        $this->categoryId = $value;
        return $this;
    }

    public function getCategoryName(): string
    {
        return HtmlDecode($this->categoryName);
    }

    public function setCategoryName(string $value): static
    {
        $this->categoryName = RemoveXss($value);
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

    public function getPicture(): mixed
    {
        return $this->picture;
    }

    public function setPicture(mixed $value): static
    {
        $this->picture = $value;
        return $this;
    }

    public function getIcon17(): mixed
    {
        return $this->icon17;
    }

    public function setIcon17(mixed $value): static
    {
        $this->icon17 = $value;
        return $this;
    }

    public function getIcon25(): mixed
    {
        return $this->icon25;
    }

    public function setIcon25(mixed $value): static
    {
        $this->icon25 = $value;
        return $this;
    }
}
