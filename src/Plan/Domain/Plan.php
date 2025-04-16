<?php

namespace DDD\Plan\Domain;

use Brick\Money\Money;

use function DDD\Bundle\generateRandomUUID;

final class Plan
{
    public function __construct(
        public readonly string $id,
        public string $name,
        public Money $price,
        public Duration $duration,
    ) {
        if ($name === '') {
            throw new \InvalidArgumentException('Name cannot be empty');
        }
        if ($price->isNegative()) {
            throw new \InvalidArgumentException('Price must be non-negative');
        }
    }

    public static function create(string $id, string $name, Money $price, Duration $duration): self
    {
        return new self(generateRandomUUID(), $name, $price, $duration);
    }
}
