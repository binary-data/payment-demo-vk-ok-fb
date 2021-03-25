<?php

declare(strict_types=1);

namespace Binarydata\PaymentDemo\Domain;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use DateTimeImmutable;

/** @Entity() */
final class Order
{
    /** @Column(type="primary") */
    private ?int $id = null;

    /** @Column(type="enum(new,pending,completed)") */
    private string $status = OrderStatus::NEW;

    /** @Column(type="integer") */
    private int $socialType;

    /** @Column(type="string") */
    private string $userId;

    /** @Column(type="integer") */
    private int $externalId;

    /** @Column(type="float", nullable=true) */
    private ?float $total = null;

    /** @Column(type="datetime", nullable = true) */
    private ?DateTimeImmutable $externalDate = null;

    /** @Column(type="datetime") */
    private DateTimeImmutable $createdAt;

    public function __construct(
        int $socialType,
        string $userId,
        int $externalId
    ) {
        $this->socialType = $socialType;
        $this->userId = $userId;
        $this->externalId = $externalId;
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExternalId(): int
    {
        return $this->externalId;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function setExternalDate(DateTimeImmutable $externalDate): void
    {
        $this->externalDate = $externalDate;
    }

    public function setTotal(float $total): void
    {
        $this->total = $total;
    }

    public function isCompleted(): bool
    {
        return $this->status === OrderStatus::COMPLETED;
    }
}
