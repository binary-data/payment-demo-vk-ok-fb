<?php

declare(strict_types=1);

namespace Binarydata\PaymentDemo\Domain\Vk;

final class VkItem
{
    public function __construct(
        private string $title,
        private string $photoUrl,
        private int $price,
        private string $id,
        private int $expiration = 0
    ) {}

    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getPhotoUrl(): string
    {
        return $this->photoUrl;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getExpiration(): int
    {
        return $this->expiration;
    }
}
