<?php declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Trait\EventsTrait;
use DateTime;
use DateTimeImmutable;

abstract class AbstractEntity
{
    use EventsTrait;

    protected DateTimeImmutable $createdAt;
    protected DateTimeImmutable $updatedAt;

    public function __construct()
    {
        $this->setUpdatedAt(new DateTimeImmutable('now'));
        $this->setCreatedAt(new DateTimeImmutable('now'));
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function updatedTimestamps(): void
    {
        $this->setUpdatedAt(new DateTimeImmutable('now'));

        if (empty($this->createdAt)) {
            $this->setCreatedAt(new DateTimeImmutable('now'));
        }
    }

    protected function setUpdatedAt(DateTimeImmutable $dateTime): void
    {
        $this->updatedAt = $dateTime;
    }

    protected function setCreatedAt(DateTimeImmutable $dateTime): void
    {
        $this->createdAt = $dateTime;
    }

}