<?php

namespace DDD\Plan\Domain;

use Brick\Money\Money;
use DDD\Bundle\Service\Random\RandomGenerator;

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
        if ($price->isNegativeOrZero()) {
            throw new \InvalidArgumentException('Price must be non-negative');
        }
        if ($duration->isNegativeOrZero()) {
            throw new \InvalidArgumentException('Duration must not be zero or lower');
        }
    }

    public static function create(string $name, Money $price, Duration $duration): self
    {
        return new self(RandomGenerator::UUID(), $name, $price, $duration);
    }
}
