<?php

namespace App\CoreAPI\Entity\Interface;

interface EntityInterface
{
    public function getId(): int;

    public function getCreatedAt(): \DateTimeImmutable;

    public function setCreatedAt(\DateTimeImmutable $createdAt): self;

    public function getUpdatedAt(): \DateTimeImmutable;

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self;

    public function getRemovedAt(): ?\DateTimeImmutable;

    public function setRemovedAt(\DateTimeImmutable $removedAt): self;
}
