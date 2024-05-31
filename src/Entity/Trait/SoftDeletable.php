<?php

namespace App\Entity\Trait;

use Doctrine\ORM\Mapping as ORM;

trait SoftDeletable
{
    #[ORM\Column(nullable: true, type: 'datetime_immutable')]
    protected ?\DateTimeImmutable $removedAt = null;

    public function isDeleted(): bool
    {
        return $this->removedAt instanceof \DateTimeImmutable;
    }

    public function recover(): void
    {
        $this->removedAt = null;
    }

    public function delete(): self
    {
        $this->setRemovedAt(new \DateTimeImmutable());

        return $this;
    }

    public function getRemovedAt(): ?\DateTimeImmutable
    {
        return $this->removedAt;
    }

    public function setRemovedAt(\DateTimeImmutable $removedAt): self
    {
        $this->removedAt = $removedAt;

        return $this;
    }
}
