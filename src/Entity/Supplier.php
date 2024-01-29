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
 * Entity class for "suppliers" table
 */
#[Entity]
#[Table(name: "suppliers")]
class Supplier extends AbstractEntity
{
    public static array $propertyNames = [
        'SupplierID' => 'supplierId',
        'CompanyName' => 'companyName',
        'ContactName' => 'contactName',
        'ContactTitle' => 'contactTitle',
        'Address' => 'address',
        'City' => 'city',
        'Region' => 'region',
        'PostalCode' => 'postalCode',
        'Country' => 'country',
        'Phone' => 'phone',
        'Fax' => 'fax',
        'HomePage' => 'homePage',
    ];

    #[Id]
    #[Column(name: "SupplierID", type: "integer", unique: true)]
    #[GeneratedValue]
    private int $supplierId;

    #[Column(name: "CompanyName", type: "string")]
    private string $companyName;

    #[Column(name: "ContactName", type: "string", nullable: true)]
    private ?string $contactName;

    #[Column(name: "ContactTitle", type: "string", nullable: true)]
    private ?string $contactTitle;

    #[Column(name: "Address", type: "string", nullable: true)]
    private ?string $address;

    #[Column(name: "City", type: "string", nullable: true)]
    private ?string $city;

    #[Column(name: "Region", type: "string", nullable: true)]
    private ?string $region;

    #[Column(name: "PostalCode", type: "string", nullable: true)]
    private ?string $postalCode;

    #[Column(name: "Country", type: "string", nullable: true)]
    private ?string $country;

    #[Column(name: "Phone", type: "string", nullable: true)]
    private ?string $phone;

    #[Column(name: "Fax", type: "string", nullable: true)]
    private ?string $fax;

    #[Column(name: "HomePage", type: "text", nullable: true)]
    private ?string $homePage;

    public function getSupplierId(): int
    {
        return $this->supplierId;
    }

    public function setSupplierId(int $value): static
    {
        $this->supplierId = $value;
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

    public function getContactName(): ?string
    {
        return HtmlDecode($this->contactName);
    }

    public function setContactName(?string $value): static
    {
        $this->contactName = RemoveXss($value);
        return $this;
    }

    public function getContactTitle(): ?string
    {
        return HtmlDecode($this->contactTitle);
    }

    public function setContactTitle(?string $value): static
    {
        $this->contactTitle = RemoveXss($value);
        return $this;
    }

    public function getAddress(): ?string
    {
        return HtmlDecode($this->address);
    }

    public function setAddress(?string $value): static
    {
        $this->address = RemoveXss($value);
        return $this;
    }

    public function getCity(): ?string
    {
        return HtmlDecode($this->city);
    }

    public function setCity(?string $value): static
    {
        $this->city = RemoveXss($value);
        return $this;
    }

    public function getRegion(): ?string
    {
        return HtmlDecode($this->region);
    }

    public function setRegion(?string $value): static
    {
        $this->region = RemoveXss($value);
        return $this;
    }

    public function getPostalCode(): ?string
    {
        return HtmlDecode($this->postalCode);
    }

    public function setPostalCode(?string $value): static
    {
        $this->postalCode = RemoveXss($value);
        return $this;
    }

    public function getCountry(): ?string
    {
        return HtmlDecode($this->country);
    }

    public function setCountry(?string $value): static
    {
        $this->country = RemoveXss($value);
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

    public function getFax(): ?string
    {
        return HtmlDecode($this->fax);
    }

    public function setFax(?string $value): static
    {
        $this->fax = RemoveXss($value);
        return $this;
    }

    public function getHomePage(): ?string
    {
        return HtmlDecode($this->homePage);
    }

    public function setHomePage(?string $value): static
    {
        $this->homePage = RemoveXss($value);
        return $this;
    }
}
