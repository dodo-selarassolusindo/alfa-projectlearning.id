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
 * Entity class for "calendar" table
 */
#[Entity]
#[Table(name: "calendar")]
class Calendar extends AbstractEntity
{
    public static array $propertyNames = [
        'Id' => 'id',
        'Title' => 'title',
        'Start' => 'start',
        'End' => 'end',
        'AllDay' => 'allDay',
        'Description' => 'description',
        'GroupId' => 'groupId',
        'Url' => 'url',
        'ClassNames' => 'classNames',
        'Display' => 'display',
        'BackgroundColor' => 'backgroundColor',
    ];

    #[Id]
    #[Column(name: "Id", type: "integer", unique: true)]
    #[GeneratedValue]
    private int $id;

    #[Column(name: "Title", type: "string")]
    private string $title;

    #[Column(name: "Start", type: "datetime")]
    private DateTime $start;

    #[Column(name: "End", type: "datetime", nullable: true)]
    private ?DateTime $end;

    #[Column(name: "AllDay", type: "boolean")]
    private bool $allDay;

    #[Column(name: "Description", type: "text", nullable: true)]
    private ?string $description;

    #[Column(name: "GroupId", type: "string", nullable: true)]
    private ?string $groupId;

    #[Column(name: "Url", type: "string", nullable: true)]
    private ?string $url;

    #[Column(name: "ClassNames", type: "string", nullable: true)]
    private ?string $classNames;

    #[Column(name: "Display", type: "string", nullable: true)]
    private ?string $display;

    #[Column(name: "BackgroundColor", type: "string", nullable: true)]
    private ?string $backgroundColor;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $value): static
    {
        $this->id = $value;
        return $this;
    }

    public function getTitle(): string
    {
        return HtmlDecode($this->title);
    }

    public function setTitle(string $value): static
    {
        $this->title = RemoveXss($value);
        return $this;
    }

    public function getStart(): DateTime
    {
        return $this->start;
    }

    public function setStart(DateTime $value): static
    {
        $this->start = $value;
        return $this;
    }

    public function getEnd(): ?DateTime
    {
        return $this->end;
    }

    public function setEnd(?DateTime $value): static
    {
        $this->end = $value;
        return $this;
    }

    public function getAllDay(): bool
    {
        return $this->allDay;
    }

    public function setAllDay(bool $value): static
    {
        $this->allDay = $value;
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

    public function getGroupId(): ?string
    {
        return HtmlDecode($this->groupId);
    }

    public function setGroupId(?string $value): static
    {
        $this->groupId = RemoveXss($value);
        return $this;
    }

    public function getUrl(): ?string
    {
        return HtmlDecode($this->url);
    }

    public function setUrl(?string $value): static
    {
        $this->url = RemoveXss($value);
        return $this;
    }

    public function getClassNames(): ?string
    {
        return HtmlDecode($this->classNames);
    }

    public function setClassNames(?string $value): static
    {
        $this->classNames = RemoveXss($value);
        return $this;
    }

    public function getDisplay(): ?string
    {
        return HtmlDecode($this->display);
    }

    public function setDisplay(?string $value): static
    {
        $this->display = RemoveXss($value);
        return $this;
    }

    public function getBackgroundColor(): ?string
    {
        return HtmlDecode($this->backgroundColor);
    }

    public function setBackgroundColor(?string $value): static
    {
        $this->backgroundColor = RemoveXss($value);
        return $this;
    }
}
