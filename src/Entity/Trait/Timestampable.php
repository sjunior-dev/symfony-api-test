<?php

namespace App\Entity\Trait;

use App\CoreAPI\Entity\Interface\EntityInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;

trait Timestampable
{
    #[ORM\Column(type: 'datetime_immutable')]
    protected \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable')]
    protected \DateTimeImmutable $updatedAt;

    public function initializeTimestamps(): void
    {
        $dateTime = new \DateTimeImmutable();

        $this->createdAt = $dateTime;
        $this->updatedAt = $dateTime;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): EntityInterface
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): EntityInterface
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    #[PreUpdate, PrePersist]
    public function refreshTimestamp(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }
}
